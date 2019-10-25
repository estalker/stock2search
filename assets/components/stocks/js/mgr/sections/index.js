Ext.onReady(function() {
    MODx.load({ xtype: 'stocks-page-home'});
});

Stocks.page.Home = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'stocks-panel-home'
            ,renderTo: 'stocks-panel-home-div'
        }]
    });
    Stocks.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(Stocks.page.Home,MODx.Component);
Ext.reg('stocks-page-home',Stocks.page.Home);