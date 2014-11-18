<?php /* Smarty version Smarty-3.1.18, created on 2014-10-18 23:30:59
         compiled from "tpl\install.html" */ ?>
<?php /*%%SmartyHeaderCode:394053df4e4c456d91-49165362%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a6294e6fc1da4d9742145b76258194fdde565d4d' => 
    array (
      0 => 'tpl\\install.html',
      1 => 1413667858,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '394053df4e4c456d91-49165362',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_53df4e4c462914_59789826',
  'variables' => 
  array (
    'step' => 0,
    'day_count' => 0,
    'lines' => 0,
    'l' => 0,
    'dirs' => 0,
    'dir' => 0,
    'stops' => 0,
    'stop' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53df4e4c462914_59789826')) {function content_53df4e4c462914_59789826($_smarty_tpl) {?><?php if ($_smarty_tpl->tpl_vars['step']->value==null) {?>
	<h1>Witaj w instalatorze ParserJazdy</h1>
	<p>
		Witaj!<br>
		Za chwilę zaczniesz instalację rozkładów jazdy!<br>
		Przed rozpoczęciem upewnij się, że rozkłady jazdy są umieszczone w katalogu <i>/install/rozklad1</i>.<br>
		Jeżeli wszystko jest gotowe, kliknij w poniższy przycisk:<br>
		<a href="?step=new_database" class="button">Przygotuj bazę danych</a><br>
		<hr>
		Reszta kroków:<br>
		<a href="?step=days" class="button">Dodaj typy dni</a>
		<a href="?step=lines" class="button">Dodaj linie</a>
		<a href="?step=directions" class="button">Dodaj kierunki linii</a>
		<a href="?step=stops_install" class="button">Dodanie wszystkich przystanków.<br>Nie stosować podczas aktualizacji!</a>
		<a href="?step=stops_update" class="button">Dodanie nowych przystanków.<br>Stosować podczas aktualizacji!</a>
		<a href="?step=infos" class="button">Dodanie oznaczeń do rozkładów</a>
		<a href="?step=deps" class="button">Dodanie odjazdów</a>
	</p>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['step']->value=='new_database') {?>
	<h1>Nowa baza danych</h1>
	<p>
		Powinno być OK.
		<a href="?step=days" class="button">Dodaj typy dni</a>
	</p>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['step']->value=='days') {?>
	<h1>Typy dni</h1>
	<p>
		<?php if ($_smarty_tpl->tpl_vars['day_count']->value>0) {?>
			Wydaje się, że instalacja typów dni jest niewymagana.<br>
			Przejdź do następnego kroku:
		<?php } else { ?>
			OK.
		<?php }?>
		<a href="?step=lines" class="button">Dodaj linie</a>
	</p>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['step']->value=='lines') {?>
	<h1>Lines</h1>
	<p>
		Jeśli wszystko poszło OK, to dodano linie do bazy danych.<br>
		Dodano następujące linie:<br>
		<?php  $_smarty_tpl->tpl_vars['l'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['l']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['lines']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['l']->key => $_smarty_tpl->tpl_vars['l']->value) {
$_smarty_tpl->tpl_vars['l']->_loop = true;
?>
			<?php echo $_smarty_tpl->tpl_vars['l']->value;?>
 
		<?php } ?>
		<a href="?step=directions" class="button">Dodaj kierunki linii</a>
	</p>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['step']->value=='directions') {?>
	<h1>Kierunki linii</h1>
	<p>
		Dodano następujące kierunki (linia ID / kierunek_nr / nazwa):<br>
		<?php  $_smarty_tpl->tpl_vars['dir'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['dir']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['dirs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['dir']->key => $_smarty_tpl->tpl_vars['dir']->value) {
$_smarty_tpl->tpl_vars['dir']->_loop = true;
?>
			<?php echo $_smarty_tpl->tpl_vars['dir']->value[0];?>
 / <?php echo $_smarty_tpl->tpl_vars['dir']->value[1];?>
 / <?php echo $_smarty_tpl->tpl_vars['dir']->value[2];?>
<br>
		<?php } ?>
		
		Wybierz krok:
		<a href="?step=stops_install" class="button">Dodanie wszystkich przystanków.<br>Nie stosować podczas aktualizacji!</a>
		<a href="?step=stops_update" class="button">Dodanie nowych przystanków.<br>Stosować podczas aktualizacji!</a>
	</p>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['step']->value=='stops_install') {?>
	<h1>Przystanki - wszystkie</h1>
	<p>
		Dodano następujące przystanki:<br>
		<?php  $_smarty_tpl->tpl_vars['stop'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['stop']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['stops']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['stop']->key => $_smarty_tpl->tpl_vars['stop']->value) {
$_smarty_tpl->tpl_vars['stop']->_loop = true;
?>
			<?php echo $_smarty_tpl->tpl_vars['stop']->value[1];?>
<br>
		<?php } ?>
		
		Kolejny krok:
		<a href="?step=infos" class="button">Dodanie oznaczeń do rozkładów</a>
	</p>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['step']->value=='stops_update') {?>
	<h1>Przystanki - aktualizacja nowych</h1>
	<p>
		Dodano następujące przystanki:<br>
		<?php  $_smarty_tpl->tpl_vars['stop'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['stop']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['stops']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['stop']->key => $_smarty_tpl->tpl_vars['stop']->value) {
$_smarty_tpl->tpl_vars['stop']->_loop = true;
?>
			<?php echo $_smarty_tpl->tpl_vars['stop']->value[1];?>
<br>
		<?php } ?>
		
		Kolejny krok:
		<a href="?step=infos" class="button">Dodanie oznaczeń do rozkładów</a>
	</p>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['step']->value=='infos') {?>
	<h1>Oznaczenia do rozkładów</h1>
	<p>
		Powinno być OK!
		
		Kolejny krok:
		<a href="?step=deps" class="button">Dodanie odjazdów</a>
	</p>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['step']->value=='deps') {?>
	<h1>Odjazdy i trasy</h1>
	<p>
		Dodanie odjazdów powinno być automatyczne.<br>
		Jeżeli wszystko się dodało, to rozkład powinien być gotowy do działania!
		
		
	</p>
<?php }?><?php }} ?>
