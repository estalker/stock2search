var Stocks = function(config) {
    config = config || {};
    Stocks.superclass.constructor.call(this,config);
};
Ext.extend(Stocks,Ext.Component,{
    page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {}
});
Ext.reg('stocks',Stocks);

Stocks = new Stocks();