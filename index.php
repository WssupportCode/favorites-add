<!-- Скрипт доавления товара в избранное -->
<script>
    $(document).ready(function () {
        $(document).on("click", ".js-add-to-favorite", function () {
            var that = $(this);
            var itemId = that.data('item');
            BX.ajax.post(
                "/ajax/add2favorite.php",
                {'item': itemId},
                function (data) {
                    data = JSON.parse(data);
                    if (data.ACTION == "ADD" && data.STATUS == true) {
                        that.addClass("active");
                    } else if (data.ACTION == "DELETE" && data.STATUS == true) {
                        that.removeClass("active");
                    }
                }
            )
        });
    )}
</script>


<div class="js-add-to-favorite"
     data-item="<?= $arResult["ID"] ?>">
                <!--  Тут необходимо передать ID товара  -->
</div>
