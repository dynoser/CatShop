var patternDateAddModal = 'Товар в продаже с {date}';
var patternDateAddList = 'в продаже с {date}';

var currentCategoryId = 0;

$(document).ready(function() {

    currentCategoryId = getUrlParams().categoryId;

    // Load categories
    $.getJSON('ajax/categories.php', function(data) {
        var categoriesList = $('#categories');
        categoriesList.empty();
        $.each(data, function(index, category) {
            var listItem = $('<li>').addClass('list-group-item d-flex justify-content-between align-items-center');
            var categoryName = $('<a>').text(category.name).attr('href', '#').attr('data-category-id', category.id);
            var countBadge = $('<span>').addClass('badge badge-primary badge-pill').text(category.count);
            listItem.append(categoryName, countBadge);
            categoriesList.append(listItem);
            if (currentCategoryId == category.id) {
                categoryName.addClass('active');
            }
        });
    });

    // Load Products when page loading
    loadProducts(currentCategoryId);

    // Load Produtcs after click on category
    $('#categories').on('click', 'a', function(event) {
        event.preventDefault();
        var categoryId = $(this).data('categoryId');
        var sortingOption = $('#sorting').val();
        updateUrlParams(categoryId, sortingOption);
        loadProducts(categoryId);
    
        // remove "active" class from previous selected category
        if (currentCategoryId) {
            $('#categories').find('a[data-category-id="' + currentCategoryId + '"]').removeClass('active');
        }
        // add "active" class to current selected category
        $(this).addClass('active');
        // change current selected category
        currentCategoryId = categoryId;
    });
    

    // Sorting produtcs by selectbox
    $('#sorting').on('change', function() {
    var categoryId = getUrlParams().categoryId;
        var sortingOption = $(this).val();
        updateUrlParams(categoryId, sortingOption);
        sortProducts(sortingOption);
    });

    // Open modal window on click "BUY"
    $('#products').on('click', '.buy-button', function(event) {
        event.preventDefault();
        var product = $(this).closest('.product-card').data('product');
        showProductModal(product);
    });
});

function loadProducts(categoryId) {
    var url = 'ajax/products.php';
    if (categoryId) {
        url += '?category_id=' + categoryId;
    }

    $.getJSON(url, function(data) {
        var productsList = $('#products');
        productsList.empty();
        $.each(data, function(index, product) {
            var DateAdd = new Date(product.date_added);
            product.DateAdd = DateAdd;
            product.unixtimeDateAdd = product.DateAdd.getTime();
            product.formattedDateAdd = product.DateAdd.toLocaleString('ru-RU', { day: 'numeric', month: 'long', year: 'numeric' });

            var card = $('<div>').addClass('col-md-4 product-card').data('product', product);
            card.attr('data-product', JSON.stringify(product));
            var image = $('<img>').attr('src', product.image);
            var title = $('<h4>').text(product.name);
            var dateAdd = $('<small>').text(patternDateAddList.replace("{date}", product.formattedDateAdd));
            var description = $('<p>').text(product.description);
            var price = $('<p>').addClass('text-success').text('$' + product.price);
            var buyButton = $('<a>').addClass('btn btn-primary btn-block buy-button').attr('href', '#').text('Купить');
            card.append(image, title, dateAdd, description, price, buyButton);
            productsList.append(card);
        });
        var sortingOption = getUrlParams().sortingOption;
        if (sortingOption) {
            sortProducts(sortingOption);
        }
    });
}

function sortProducts(sortingOption) {
    var productsList = $('#products');
    var products = productsList.children('.product-card').get();
    products.sort(function(a, b) {
        var aData = $(a).data('product');
        var bData = $(b).data('product');
        switch (sortingOption) {
            case 'price_asc':
                return aData.price - bData.price;
            case 'alphabetical':
                return aData.name.localeCompare(bData.name);
            case 'newest':
                return aData.unixtimeDateAdd - bData.unixtimeDateAdd;
            default:
                return 0;
        }
    });

    productsList.empty();
    $.each(products, function(index, product) {
        productsList.append(product);
    });
}

function showProductModal(product) {
    var modal = $('#productModal');
    var modalContent = $('#productModalContent');
    modalContent.empty();
    var image = $('<img>').attr('src', product.image);
    var title = $('<h4>').text(product.name);
    var dateAdd = $('<small>').text(patternDateAddModal.replace('{date}', product.formattedDateAdd));
    var description = $('<p>').text(product.description);
    var price = $('<p>').addClass('text-success').text('$' + product.price);
    var closeButton = $('<button>').addClass('btn btn-secondary').attr('type', 'button').attr('data-dismiss', 'modal').text('Close');
    modalContent.append(image, title, description, dateAdd, price, closeButton);
    modal.modal('show');
}


function getUrlParams() {
    var params = new URLSearchParams(window.location.search);
    return {
        categoryId: params.get('category_id'),
        sortingOption: params.get('sorting')
    };
}

function updateUrlParams(categoryId, sortingOption) {
    var searchParams = new URLSearchParams();
    var existingCategoryId = getUrlParams().categoryId;
    var existingSortingOption = getUrlParams().sortingOption;

    if (existingSortingOption && !sortingOption) {
        searchParams.set('category_id', categoryId);
    } else {
        searchParams.set('category_id', categoryId);
        searchParams.set('sorting', sortingOption);
    }

    var newUrl = window.location.pathname + '?' + searchParams.toString();
    window.history.pushState({}, '', newUrl);
}

$(window).on('popstate', function(event) {
    var state = event.originalEvent.state;
    if (state) {
        var categoryId = state.categoryId;
        var sortingOption = state.sortingOption;
        if (categoryId) {
            loadProducts(categoryId);
        } else {
            loadProducts();
        }
        if (sortingOption) {
            sortProducts(sortingOption);
        }
    }
});
