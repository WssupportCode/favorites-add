<!-- Скрипт доавления товара в избранное -->
<script>
    $(document).ready(function () {
        if (typeof arFavorites !== "undefined") {
            arFavorites.forEach(function (item, i) {
                var product = $('.js-add-to-favorite[data-item="' + item + '"]');
                if (!product.hasClass('active')) {
                    product.addClass('active');
                }
            })
        }

        $(document).on("click", ".js-add-to-favorite", function () {
            var that = $(this);
            var itemId = that.data('item');
            var reload = that.data('reload')
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
                    refreshSmallFavorite();
                    if (reload == "Y") {
                        window.location.reload()
                    }
                }
            )
        });

    function refreshFavorite() {
        BX.ajax.get(
            document.location.pathname,
            {"REFRESH_SMALL_FAVORITE": "Y"},
            function (data) {
                $(".js-favorite-refresh").html(data)
            }
        )
    }
</script>


<div class="js-add-to-favorite"
     data-item="<?= $arResult["ID"] ?>">
                <!--  Тут необходимо передать ID товара  -->
</div>
