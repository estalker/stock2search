Stocks.panel.Home = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,cls: 'container'
        ,items: [{
            html: '<h2>'+_('stocks.management')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-tabs'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,items: [{
                title: _('stocks')
                ,defaults: { autoHeight: true }
                ,items: [{
                    xtype: 'stocks-grid-stocks'
                    ,cls: 'main-wrapper'
                    ,preventRender: true
                }]
            }]
        }]
    });
    Stocks.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(Stocks.panel.Home,MODx.Panel);
Ext.reg('stocks-panel-home',Stocks.panel.Home);
