<?php

/**
 * Disabled input for property
 *
 * TODO : use it on hook to avoid loop
 * @package    the_Inventory
 * @subpackage the_Inventory/admin/partials
 */
?>

<div class="pods-form-fields">
  <input type="text" disabled="disabled" name="name" value="<?php echo $propertyDefinition->display('name') ?>"/>
  <input type="text" disabled="disabled" name="value" value="<?php echo $propertyInstance->display('value') ?>"/>
  <input type="text" disabled="disabled" name="unit" value="<?php echo $propertyDefinition->display('unit') ?>"/>
  <input type="hidden" name="property_id" value="<?php echo $propertyDefinition->field('id') ?>"/>
  <input type="hidden" name="propertyInstance_id" value="<?php echo $propertyInstance_id ?>"/>
  <button type="button" class="edit-prop button" ><?php echo __('Edit', 'the-inventory') ?></button>
  <button type="button" class="delete-prop button" ><?php echo __('Delete', 'the-inventory') ?></button>
</div>
