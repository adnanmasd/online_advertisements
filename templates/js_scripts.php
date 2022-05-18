<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script type="text/javascript" src="./web/js/jquery.js"></script>
<script type="text/javascript" src="./web/js/jquery-ui.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script type="text/javascript" src="./web/js/bootstrap.min.js"></script>
<script type="text/javascript" src="./web/js/parsley.min.js"></script>

<script type="text/javascript">
    $(function () {
        $("#searchInput").autocomplete({
            source: './scripts/autocomplete.php',
            minLength: 2,
            focus: function (event, ui) {
                $("#searchInput").val(ui.item.keyword);
                return false;
            },
            select: function (event, ui) {
                var link = '/ad_site/search.php?keyword=' + ui.item.keyword;
                if (ui.item.cat !== null) {
                    link += '&category=' + ui.item.cat;
                }
                window.location = link;
            }
        }).data("ui-autocomplete")._renderItem = function (ul, item) {
            return $("<li></li>")
                    .data("item.keyword", item)
                    .append("<a><strong>" + item.keyword + "</strong> in <span style='color:blue;text-decoration:underline;'>" + item.category + "</span></a>")
                    .appendTo(ul);
        };
    });
</script>
