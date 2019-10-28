Stocks.grid.Stocks = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'stocks-grid-stocks'
        ,url: Stocks.config.connectorUrl
        ,baseParams: { action: 'mgr/stock/getList' }
        ,fields: ['id','vendor', 'number', 'price','count','name','filedate']
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,anchor: '97%'
        ,autoExpandColumn: 'name'
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,sortable: true
            ,width: 40
        },{
            header: _('stocks.vendor')
            ,dataIndex: 'vendor'
            ,sortable: true
            ,width: 100
        },{
            header: _('stocks.number')
            ,dataIndex: 'number'
            ,sortable: true
            ,width: 150
        },{
            header: _('stocks.price')
            ,dataIndex: 'price'
            ,sortable: true
            ,width: 80
        },{
            header: _('stocks.count')
            ,dataIndex: 'count'
            ,sortable: true
            ,width: 80
        },{
            header: _('stocks.name')
            ,dataIndex: 'name'
            ,sortable: true
            ,width: 200
        },{
            header: _('stocks.filedate')
            ,dataIndex: 'filedate'
            ,sortable: true
            ,width: 80
        }]
        ,tbar: ['->',{
            xtype: 'textfield'
            ,id: 'stocks-search-filter'
            ,emptyText: _('stocks.search...')
            ,listeners: {
                'change': {fn:this.search,scope:this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: function() {
                            this.fireEvent('change',this);
                            this.blur();
                            return true;
                        }
                        ,scope: cmp
                    });
                },scope:this}
            }
        }]
    });
    Stocks.grid.Stocks.superclass.constructor.call(this,config)
};
Ext.extend(Stocks.grid.Stocks,MODx.grid.Grid,{
    search: function(tf,nv,ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
});
Ext.reg('stocks-grid-stocks',Stocks.grid.Stocks);