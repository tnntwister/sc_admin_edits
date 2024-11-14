<div class="form-group">
    <hr>
    <label class="control-label col-lg-3" for="permanently_out_of_stock">
        {$smarty.const._PS_VERSION_ < '1.7.7.0' ? $this->l('Permanently out of stock', 'Admin.Catalog.Feature') : l s='Permanently out of stock' d='Admin.Catalog.Feature'}
    </label>
    <div class="col-lg-9">
        <span class="switch prestashop-switch fixed-width-lg">
            <input type="radio" name="permanently_out_of_stock" id="permanently_out_of_stock_on" value="1" {if $permanently_out_of_stock}checked="checked"{/if}>
            <label for="permanently_out_of_stock_on">
                {$smarty.const._PS_VERSION_ < '1.7.7.0' ? $this->l('Enabled', 'Admin.Global') : l s='Enabled' d='Admin.Global'}
            </label>
            <input type="radio" name="permanently_out_of_stock" id="permanently_out_of_stock_off" value="0" {if !$permanently_out_of_stock}checked="checked"{/if}>
            <label for="permanently_out_of_stock_off">
                {$smarty.const._PS_VERSION_ < '1.7.7.0' ? $this->l('Disabled', 'Admin.Global') : l s='Disabled' d='Admin.Global'}
            </label>
        </span>
    </div>
    <hr>
</div>