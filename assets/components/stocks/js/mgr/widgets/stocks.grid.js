Stocks.grid.Stocks = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'stocks-grid-stocks'
        ,url: Stocks.config.connectorUrl
        ,baseParams: { action: 'mgr/stock/getList' }
        ,save_action: 'mgr/stock/updateFromGrid'
        ,fields: ['id','name','description']
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,anchor: '97%'
        ,autoExpandColumn: 'name'
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,sortable: true
            ,width: 60
        },{
            header: _('stocks.name')
            ,dataIndex: 'name'
            ,sortable: true
            ,width: 100
            ,editor: { xtype: 'textfield' }
        },{
            header: _('stocks.description')
            ,dataIndex: 'description'
            ,sortable: false
            ,width: 350
            ,editor: { xtype: 'textfield' }
        }]
        ,tbar: [{
            text: _('stocks.stock_create')
            ,handler: { xtype: 'stocks-window-stock-create' ,blankValues: true }
        },'->',{
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
    ,getMenu: function() {
        return [{
            text: _('stocks.stock_update')
            ,handler: this.updateStock
        },'-',{
            text: _('stocks.stock_remove')
            ,handler: this.removeStock
        }];
    }
    ,updateStock: function(btn,e) {
        if (!this.updateStockWindow) {
            this.updateStockWindow = MODx.load({
                xtype: 'stocks-window-stock-update'
                ,record: this.menu.record
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        }
        this.updateStockWindow.setValues(this.menu.record);
        this.updateStockWindow.show(e.target);
    }

    ,removeStock: function() {
        MODx.msg.confirm({
            title: _('stocks.stock_remove')
            ,text: _('stocks.stock_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/stock/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }
});
Ext.reg('stocks-grid-stocks',Stocks.grid.Stocks);


Stocks.window.CreateStock = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('stocks.stock_create')
        ,url: Stocks.config.connectorUrl
        ,baseParams: {
            action: 'mgr/stock/create'
        }
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('stocks.name')
            ,name: 'name'
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,fieldLabel: _('stocks.description')
            ,name: 'description'
            ,anchor: '100%'
        }]
    });
    Stocks.window.CreateStock.superclass.constructor.call(this,config);
};
Ext.extend(Stocks.window.CreateStock,MODx.Window);
Ext.reg('stocks-window-stock-create',Stocks.window.CreateStock);


Stocks.window.UpdateStock = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('stocks.stock_update')
        ,url: Stocks.config.connectorUrl
        ,baseParams: {
            action: 'mgr/stock/update'
        }
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('stocks.name')
            ,name: 'name'
            ,anchor: '100%'
        },{
            xtype: 'textarea'
            ,fieldLabel: _('stocks.description')
            ,name: 'description'
            ,anchor: '100%'
        }]
    });
    Stocks.window.UpdateStock.superclass.constructor.call(this,config);
};
Ext.extend(Stocks.window.UpdateStock,MODx.Window);
Ext.reg('stocks-window-stock-update',Stocks.window.UpdateStock);