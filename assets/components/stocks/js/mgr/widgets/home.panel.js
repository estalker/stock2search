Stocks.panel.Home = function(config) {
    config = config || {};
    Ext.apply(config,{
        id: 'stock-panel-cmp'
        ,border: false
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
            ,items: [
		{
                title: _('stocks')
                ,defaults: { autoHeight: true }
                ,items: [		
			{
                    xtype: 'form'
                    ,id: 'import_form'
                    ,border: false
	            ,padding: 20
                    ,labelWidth: 180
                    ,buttonAlign: 'left'
                    ,items: [
                        {
                            xtype: 'hidden'
                            ,name: 'current_string'
                            ,id: 'current_string'
                            ,value: '0'
                        }]			
		},
		{
                    xtype: 'stocks-grid-stocks'
                    ,cls: 'main-wrapper'
                    ,preventRender: true
 	              }]
		
                }]
        }]
    });
    Stocks.panel.Home.superclass.constructor.call(this,config);
};
Ext.extend(Stocks.panel.Home,MODx.Panel,{
    importLastFile:function(skip,total,is_first){
        var root = this;
        if(typeof(skip)=='undefined') var skip = 0;
        if(typeof(total)=='undefined') var total = 0;
        if(typeof(is_first)=='undefined') var is_first = 1;
        var imp_form = Ext.getCmp('import_form');
        var formValues = imp_form.getForm().getValues();
        formValues.skip = skip;
        formValues.is_first = is_first;

        var process = skip > 0 && skip < total ? Math.round((1 - ((total - skip) / total)) * 100) : 0;
        var process_str = process>0 && process<100 ? ' ('+process+'%)' : '';
        imp_form.getEl().mask(_('loading')+process_str, 'x-mask-loading');


        Ext.Ajax.request({
            url: Stocks.config.connectorUrl
            ,params: {action: "import", data: Ext.encode(formValues)}
            ,method: 'POST'
            ,success: function(response, options){
                
                Ext.getCmp('import_form').getEl().unmask();
                
                var result = Ext.util.JSON.decode(response.responseText);
                
                if(typeof(result) == 'object'){
                    
                    if(result.success==false){
                        Ext.Msg.alert(_('stocks_message'), result.message);
                        return;
                    }
                    
                    if(result.object.pos < result.object.lines_count){
                        
                        Ext.getCmp('current_string').setValue(result.object.pos);
                        
                        Ext.getCmp('stock-panel-cmp').importLastFile(result.object.pos, result.object.lines_count, 0);
                        
                    }else{
                        
                        imp_form.getForm().reset();
                        Ext.getCmp('modx-resource-tree').refresh();
                        MODx.clearCache();
			Ext.getCmp('stocks-grid-stocks').refresh();
                    }
                    
                }
                
                            	
            }
            ,failure: function(response, options){
                
                Ext.getCmp('import_form').getEl().unmask();
                Ext.Msg.alert(_('stocks_message'), 'Error '+response.status);
                
            }
            
        });
    }
});
Ext.reg('stocks-panel-home',Stocks.panel.Home);
