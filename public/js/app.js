define(["jQuery", "jqx-all"], function () {
    var initialize = function () {
        $(document).ready(function () {
            $('#jqxTree').jqxTree({ height: '300', width: '300' });
            $('#jqxTree').css("visibility", "visible");
        });
    };
    return {
        initialize: initialize
    };
});