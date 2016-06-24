require.config({
    paths: {
        "jQuery": "jquery.min",
        "jqx-all": "jqx-all"
    },
    shim: {
        "jqx-all": {
            export: "$",
            deps: ['jQuery']
        }
    }
});
require(["app"], function (App) {
    App.initialize();
});