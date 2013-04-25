<?php $this->breadcrumbs = array($this->module->id);?>

<div class="box">
  <h1>SensorarioUrlRoute</h1>
  <a href="<?php echo $this->createUrl('generate');?>">Generate routing file</a> |
  <a href="<?php echo $this->createUrl('delete');?>">Erase all routes</a>
</div>

<div class="box">
  <h1>Active routes</h1>
  <?php highlight_string($routes);?>
</div>
