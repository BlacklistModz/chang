<?php

$form = new Form();
$form = $form->create()
	// set From
	->elem('div')
	->addClass('form-insert');

  // $form 	->field("cus_id")
  // ->label('Customer No : ')
  // ->autocomplete('off')
  // ->addClass('inputtext')
  // ->placeholder('')
  // ->value( !empty($this->item['id'])? $this->item['id']:'' );

  $form 	->field("cus_name")
  ->label('Customer Name* : ')
  ->autocomplete('off')
  ->addClass('inputtext')
  ->placeholder('')
  ->value( !empty($this->item['name'])? $this->item['name']:'' );

  $form 	->field("cus_address")
  ->label('Address* : ')
  ->autocomplete('off')
  ->addClass('inputtext')
  ->placeholder('')
  ->value( !empty($this->item['address'])? $this->item['address']:'' );

  $form 	->field("cus_postcode")
  ->label('Post code* : ')
  ->autocomplete('off')
  ->addClass('inputtext')
  ->placeholder('')
  ->value( !empty($this->item['postcode'])? $this->item['postcode']:'' );

  $form 	->field("cus_contact_name")
  ->label('Contact Name* : ')
  ->autocomplete('off')
  ->addClass('inputtext')
  ->placeholder('')
  ->value( !empty($this->item['contact_name'])? $this->item['contact_name']:'' );

  $form 	->field("cus_sale_id")
  ->label('Sale Name* : ')
  ->autocomplete('off')
  ->addClass('inputtext')
  ->placeholder('')
  ->select( $this->sale['lists'], 'id', 'fullname' )
  ->value( !empty($this->item['sale_id'])? $this->item['sale_id']:'' );

  $form 	->field("cus_group")
  ->label('Customer Group : ')
  ->autocomplete('off')
  ->addClass('inputtext')
  ->placeholder('')
  ->select( $this->group )
  ->value( !empty($this->item['group'])? $this->item['group']:'' );

  $form 	->field("cus_payment_term")
  ->label('Payment Term : ')
  ->autocomplete('off')
  ->addClass('inputtext')
  ->placeholder('')
  ->value( !empty($this->item['payment_term'])? $this->item['payment_term']:'' );

  $form 	->field("cus_incoterm")
  ->label('Incoterm : ')
  ->autocomplete('off')
  ->addClass('inputtext')
  ->placeholder('')
  ->value( !empty($this->item['incoterm'])? $this->item['incoterm']:'' );

  $form 	->field("cus_status")
  ->label('Status* : ')
  ->autocomplete('off')
  ->addClass('inputtext')
  ->placeholder('')
  ->select($this->status)
  ->value( !empty($this->item['status'])? $this->item['status']:'' );


//******************************************************************************

$form2 = new Form();
$form2 = $form2->create()
	// set From
	->elem('div')
	->addClass('form-insert');


  $form2 ->field("cus_tax_id")
  ->label('Tax ID : ')
  ->autocomplete('off')
  ->addClass('inputtext')
  ->placeholder('')
  ->value( !empty($this->item['tax_id'])? $this->item['tax_id']:'' );

  $form2 ->field("cus_branch")
  ->label('Branch : ')
  ->autocomplete('off')
  ->addClass('inputtext')
  ->placeholder('')
  ->select( $this->branch , 'id','name')
  ->value( isset($this->item['branch'])? $this->item['branch']:'00000' );

  $form2 ->field("cus_province")
  ->label('Province : ')
  ->autocomplete('off')
  ->addClass('inputtext')
  ->placeholder('')
  ->value( !empty($this->item['province'])? $this->item['province']:'' );

  $form2 ->field("cus_country")
  ->label('Country : ')
  ->autocomplete('off')
  ->addClass('inputtext')
  ->placeholder('')
  ->select( $this->country )
  ->value( !empty($this->item['country'])? $this->item['country']:'' );

  $form2 ->field("cus_brand")
  ->label('Brand : ')
  ->autocomplete('off')
  ->addClass('inputtext')
  ->placeholder('')
  ->select( $this->brand['lists'] )
  ->value( !empty($this->item['brand'])? $this->item['brand']:'' );

  $form2 ->field("cus_contact_phone")
  ->label('Contact Phone : ')
  ->autocomplete('off')
  ->addClass('inputtext')
  ->placeholder('')
  ->value( !empty($this->item['contact_phone'])? $this->item['contact_phone']:'' );

  $form2 ->field("cus_contact_fax")
  ->label('Contact Fax : ')
  ->autocomplete('off')
  ->addClass('inputtext')
  ->placeholder('')
  ->value( !empty($this->item['contact_fax'])? $this->item['contact_fax']:'' );

  $form2 ->field("cus_contact_email")
  ->label('Contact E-mail : ')
  ->autocomplete('off')
  ->addClass('inputtext')
  ->placeholder('')
  ->value( !empty($this->item['contact_email'])? $this->item['contact_email']:'' );

  $form2 ->field("cus_currency")
  ->label('Currency : ')
  ->autocomplete('off')
  ->addClass('inputtext')
  ->placeholder('')
  ->select( $this->currency )
  ->value( !empty($this->item['currency'])? $this->item['currency']:'' );

  $title = "Create";
  if( !empty($this->item) ){
  	$title = "Edit";
  }
 ?>
<div class="pal">

	<div class="setting-header cleafix">
		<div class="setting-title"><?=$title?> Customer</div>
	</div>

	<section class="setting-section">
		<form class="js-submit-form" method="post" action="<?=URL?>customers/save">

          <table style="width:100%; height:100%;" class="uiBoxLightblue">
						<tr>
							<td style="height:100%; width:50%; margin-top:10px; padding:20px;">
                <div class="uiBoxWhite pam pas">
                  <?=$form->html();?>
                </div>
							</td>
							<td style="height:100%; width:50%; margin-top:10px; padding:20px;">
                <div class="uiBoxWhite pam pas">
                  <?=$form2->html();?>
                </div>
              </td>
						</tr>
          </table>

          <div style="margin-top:30px; width:100%; text-align:center; position:relative; bottom:20px">
            <button type="submit" class="btn btn-primary btn-submit"><span class="btn-text"> Save</span></button>
            <a class="btn" role="dialog-close" href="<?=URL?>settings/accounts/customers"><span class="btn-text">Cancel</span></a>
          </div>

          <?php 
          if( !empty($this->item) ){
          	echo '<input type="hidden" name="id" value="'.$this->item['id'].'">';
          }
          ?>
        </form>
	</section>
</div>