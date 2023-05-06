<!-- Gestion des alertes -->
<div id="alertContainer" class="font-S">
	<?php
	if (!empty($this->session->flashdata('success'))) : ?>
		<div class="alert bgVertVerisure text-light alert-dismissible fade show" role="alert" id="alertMessageSuccess">
			<?= $this->session->flashdata('success') ?>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	<?php endif; ?>
	<?php
	if(!empty($this->session->flashdata('error'))) : ?>
		<div class="fontsize13 alert bgRougeVerisure text-light alert-dismissible fade show" role="alert" id="alertMessageError">
			<?= $this->session->flashdata('error') ?>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	<?php endif;?>
</div>


<!--DASHBOARD -->
<div id="key-datas" class="mt-5 container-fluid">
	<section class="d-flex w-100 justify-content-between mb-5">
		<h2 class="table-verisure-title">Dashboard des Keys Datas</h2>
	</section>
	<section class="newKeyData d-flex align-items-center m-2">
		<button id="addNewKeyData" class="bgJauneVerisure text-white d-flex align-items-center" title="Ajouter une key data">
			<p class="font-L">Ajouter une nouvelle key data</p>
			<i class="far fa-plus-square ml-4 font-icon"></i>
		</button>
	</section>

	<!--TABLEAU DES ACTIONS -->
	<section class="m-2 mt-5 w-80">
		<table id="table-keyDatas" class="table font-S">
			<thead>
				<tr>
					<!-- td ID key data -->
					<th scope="col col-md-0" class="d-none">id</th>
					<!-------->
					<th scope="col  col-md-3">Nom</th>
					<th scope="col  col-md-3">Contenu</th>
					<th scope="col  col-md-1" class="updateSize">Dernière mise à jour</th>
					<th scope="col  col-md-1">Actions</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($keyDatas as $data) : ?>
				<tr>
					<!-- td ID key data -->
					<td class="d-none col-md-0"><input type="text" value="<?= htmlentities($data->id); ?>" name="id" name="id"/> </td>
					<!-------->
					<td class="col-md-3"><input type="text" value="<?= htmlentities($data->name); ?>" name="name" name="name" class="form-control" readonly/></td>
					<td class="col-md-3"><input type="text" value="<?= htmlentities($data->content); ?>" name="content" name="content" class=" form-control" readonly/></td>
					<td class="col-md-1 updateSize"><input type="text" value="<?= htmlentities($data->date_modification); ?>" name="date_modification" name="date_modification" class="form-control" readonly/></td>
					<td class="col-md-1">
						<div class="d-flex justify-content-around">
							<i class="fas fa-edit textBleuVerisure editKeyData" title="Editer une key data" data-id="<?= htmlentities($data->id); ?>" data-name=""></i>
							<i class="fas fa-trash-alt textRougeVerisure deleteKeyData" title="Supprimer une key data" data-toggle="modal" data-target="#deleteKeyDataModal" data-id="<?= htmlentities($data->id); ?>" data-name="<?= htmlentities($data->name); ?>" data-content="<?= htmlentities($data->content); ?>"></i>
						</div>
					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<div class="paginate d-flex justify-content-end">
			<?php if(isset($links)) : ?>
				<p class="font-S mr-4"><?php echo $links; ?></p>
			<?php endif; ?>
		</div>
	</section>
</div>


<!-- Modal suppression offre -->
<div class="modal fade" id="deleteKeyDataModal" tabindex="-1" role="dialog" aria-labelledby="deleteKeyDataModal" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title textRougeVerisure font-M"><i class="pr-2 fas fa-trash-alt textRougeVerisure"></i>Supprimer une key data</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span class="textDarkGreyVerisure font-L" aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="<?= base_url().'KeyDatas/delete'?>" method="POST">
				<div class="modal-body font-S">
					<p class="font-M text-center">Êtes-vous sûr de vouloir supprimer la key data suivante :
						<br>
						<span class="font-weight-bold textRougeVerisure" id="delete_nameKeyData"></span> : <span class="textRougeVerisure" id="delete_contentKeyData"></span> ?</p><br />
					<input type="hidden" name="id" value="" id="delete_idKeyData">
					<input type="hidden" name="name" value="" id="delete_name_KeyData">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn bgDarkGreyVerisure text-light font-S" data-dismiss="modal">Annuler</button>
					<button type="submit" class="btn bgRougeVerisure text-light font-S">Supprimer</button>
				</div>
			</form>
		</div>
	</div>
</div>
