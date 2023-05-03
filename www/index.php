<?php
  // preset global JS values
  $patternsArr = [
    'patternDateAddModal' => 'Date added: {date}', // 'Товар в продаже с {date}',
    'patternDateAddList' => 'date added: {date}', //'в продаже с {date}',
    'patternPrice' => '{price}₴', // формат вывода цены
    'toFixedPrice' => 0, // до скольки знаков после запятой обрезается цена (в базе хранятся 2 знака после запятой)
    'dateLocale' => 'en-US', //'ru-RU',
    'buyText' => 'BUY', //'Купить'
  ];

  // Sort options array
  $sortOptionsArr = [
    'price_asc' => 'By price increase', //'От дешевых к дорогим',
    'alphabetical' => 'By Alphabet', //'По алфавиту',
    'newest' => 'Newer first', //'Сначала более новые',
  ];

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Internet Shop</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
<?php
  foreach($patternsArr as $varName => $varValue) {
    echo "var $varName = ";
    echo is_numeric($varValue) ? $varValue : "'$varValue'";
    echo ";\n";
  }
?>
    </script>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h2>Categories</h2>
            <ul id="categories" class="list-group">
                <!-- Categories list will generated -->
            </ul>
        </div>
        <div class="col-md-8">
            <h2>Products</h2>
            <select id="sorting" class="form-control mb-3">
              <?php
              foreach($sortOptionsArr as $key => $text) {
                echo '<option value="' . $key . '">' . $text . '</option>';
              }
              ?>
            </select>
            <div id="products" class="row">
                <!-- Item list will generated -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Widows -->
<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Товар</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="productModalContent">
        <!-- Content will generated -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="scripts.js"></script>
</body>
</html>
