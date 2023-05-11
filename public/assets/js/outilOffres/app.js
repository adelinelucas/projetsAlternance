document.addEventListener('DOMContentLoaded', () => {
	// FONCTIONS GENERALES
	// gestion des alertes
	const displayAlertInfo = (successOrError, dataName, action) =>{
		let alertEl = document.createElement('div');
		let alertContainer = document.getElementById('alertContainer')
		let alertBackground = '';
		let message = '';
		if(successOrError === 'success'){
			alertBackground = 'bgVertVerisure';
			message = `La key data "${dataName}" a bien été`;
			if(action === 'update'){
				message += ` mise à jour.`
			}else{
				message += ` créée.`
			}
		}else{
			alertBackground = 'bgRougeVerisure';
			message = `Une erreur est survenue, la key data "${dataName}" n\'a pas pu être`;
			if(action === 'update'){
				message += ` mise à jour.`
			}else{
				message += ` créée.`
			}
		}
		alertEl.innerHTML = `<div class="alert ${alertBackground} text-light alert-dismissible fade show" role="alert" id="alertMessageSuccess">
			<p>${message}</p>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
		</div>`;
		alertContainer.appendChild(alertEl);
	}

	const manageDisplayAlertInfo = () =>{
		let alertMessageSuccess = document.getElementById('alertMessageSuccess');
		let alertMessageError = document.getElementById('alertMessageError');
		let closeBtn = null;
		if(alertMessageError){
			closeBtn = alertMessageError.querySelector('button')
		}
		if(alertMessageSuccess){
			closeBtn = alertMessageSuccess.querySelector('button')
		}
		if(closeBtn){
			closeAlert(closeBtn);
		}
	}

	const closeAlert = (closeBtn) =>{
		setTimeout( ()=>{
			closeBtn.click();
			location.reload();
		}, 1000)
	}

	// gestion de l'édition
	const toggleReadOnly = (inputs, editBtn, cancelUpdate = true) =>{
		if(!editBtn.classList.contains('fa-edit')){
			inputs[1].removeAttribute('readOnly');
			inputs[2].removeAttribute('readOnly');
		}else{
			inputs[1].setAttribute('readOnly', true);
			inputs[2].setAttribute('readOnly', true);
			if(cancelUpdate) backToInitialValue(inputs);
		}
	}

	const toggleEditIcon = (editBtn) =>{
		if(editBtn.classList.contains('fa-edit')){
			editBtn.classList.remove('fa-edit');
			editBtn.classList.add('fa-window-close');
			editBtn.classList.add('textDarkGreyVerisure');
			editBtn.style.fontSize = '2.2rem';
			editBtn.style.cursor = 'pointer';
		}else{
			editBtn.classList.add('fa-edit');
			editBtn.classList.remove('fa-window-close');
			editBtn.classList.remove('textDarkGreyVerisure');
		}

	}

	const backToInitialValue = (inputs) =>{
		for (const input of inputs) {
			input.value = input.defaultValue
		}
	}

	const clearEmptyInputMessage = () =>{
		let messages = document.querySelectorAll('.infoInput');
		if(messages){
			for (const message of messages) {
				message.remove()
			}
		}
	}

	// VERIFICATION QUE LES INPUTS NE SONT PAS VIDES
	const emptyInputMessage = (input) =>{
		let messageEl = document.createElement('div');
		messageEl.classList.add('infoInput');
		messageEl.innerHTML = `<i class="fas fa-exclamation textRougeVerisure font-S"></i><p class="textRougeVerisure font-S">Ce champ ne peut pas être vide.</p>`
		let tdEl = input.parentElement;
		tdEl.appendChild(messageEl);
	}

	const checkEmptyInput = (inputs,  isEmptyInput=false) =>{
		clearEmptyInputMessage();
		for (const input of inputs) {
			if(input.name === 'name' || input.name === 'content'){
				if((input.value).length === 0){
					emptyInputMessage(input);
					isEmptyInput = true;
				}
			}
		}
		return isEmptyInput;
	}

	// envoie des données pour create & update en fetch
	async function fetch_updateKeyData(datas, action) {
		fetch(`/outilOffres/${action}/`, {
			method: 'POST',
			body: JSON.stringify(datas),
			headers: {
				'Accept': 'application/json',
				"Content-Type": "application/json"
			}
		})
		.then((response)=> {
			if(response.status === 500 || response.status === 400){
				throw new Error();
			}
			displayAlertInfo('success', datas.name, action);
			manageDisplayAlertInfo();
		})
		.catch(err => {
			displayAlertInfo('error', datas.name, action);
			manageDisplayAlertInfo();
		});
	}

	// Check si le nom de la key data est unique pour éviter les doublons
	async function checkNameIsUnique (name) {
		let nameIsUnique = false;
		if(name){
			let response =  await fetch(`/outilOffres/checkNameIsUnique/?name=${name}`, {
				method: 'GET',
				headers: {
					'Accept': 'application/json',
					"Content-Type": "application/json"
				}
			});
			nameIsUnique = await response.json();
		}
		console.log(nameIsUnique)
		return nameIsUnique;
	}

	// vérification du doublon concernant le name de la key data
	const keyDataDoublonInfo = (input) =>{
		let messageEl = document.createElement('div');
		messageEl.classList.add('infoInput');
		messageEl.innerHTML = `<i class="fas fa-exclamation textRougeVerisure font-S"></i><p class="textRougeVerisure font-S">Cette key data existe déjà, pour éviter les doublons merci de mettre à jour la key data correspondante ou de modifier le nom de la key data.</p>`
		let tdEl = input.parentElement;
		tdEl.appendChild(messageEl);
	}

	async function isKeyDataNameUnique (nameInput, datas, action) {
		let nameIsUnique = await checkNameIsUnique(nameInput.value)
		console.log(nameIsUnique);
		nameInput.addEventListener('keyup', ()=>{
			clearEmptyInputMessage();
		})
		if(nameIsUnique){
			fetch_updateKeyData(datas, action);
		}else{
			keyDataDoublonInfo(nameInput);
		}
		return nameIsUnique;
	}

	////
	// UPDATE KEY DATA
	////
	const sendKeyDataUpdated = (btn, inputs) =>{
		let isEmptyInput = false;

		btn.addEventListener('click', ()=>{
			let isConfirmIconExist = btn.parentElement.querySelector('.fa-check-square');
			let datas = {};
			let inputName;
			// variable pour checker sur le nom de la key data a changé lors de l'update
			let keyDataNameHasNotChanged = false;
			for (const input of inputs) {
				datas[input.name]= input.value;
			}
			checkEmptyInput(inputs, isEmptyInput);
			if(checkEmptyInput(inputs, isEmptyInput)){
				return;
			}else{
				let arrayValueHasNotChanged = [];
				// vérification si les données ont changé vs initiales values
				for (const input of inputs) {
					if(input.name === 'content') {
						if(input.value === input.defaultValue) arrayValueHasNotChanged.push(true)
					}
					if(input.name === 'name') {
						inputName = input;
						if(input.value === input.defaultValue) {
							arrayValueHasNotChanged.push(true);
							keyDataNameHasNotChanged = true;
						}

					}
				}
				let btnEdit = btn.parentElement.querySelector('.editKeyData');
				let uniqueName = false;
				if(arrayValueHasNotChanged.length <= 1){
					if(keyDataNameHasNotChanged) {
						// si le nom n'a pas changé on met à jour sans vérifier l'existence d'un doublon
						fetch_updateKeyData(datas, 'update');
					}
					else{
						// si le nom de la key data a changé on vérifie qu'il est unique
						const isNameUniqueReturn = async () =>{
							uniqueName = await isKeyDataNameUnique(inputName, datas, 'update');
							if(uniqueName){
								toggleEditIcon(btnEdit);
								toggleReadOnly(inputs, btnEdit, false);

								if(isConfirmIconExist) isConfirmIconExist.parentElement.remove();
							}
							return;
						}
						isNameUniqueReturn();
						return;
					}
				}
				toggleEditIcon(btnEdit);
				toggleReadOnly(inputs, btnEdit, false);

				if(isConfirmIconExist) isConfirmIconExist.parentElement.remove();
			}
		})
	}

	const addConfirmIconAndUpdate = (btn, addEl=true, inputs) =>{
		if(addEl){
			let confirmBtn = document.createElement('div');
			confirmBtn.innerHTML = '<i class="fas fa-check-square textVertVerisure font-icon cursorPointerOn" title="Modifier la key data"></i>';
			btn.after(confirmBtn);
			//soumission du form
			sendKeyDataUpdated(confirmBtn, inputs)
		}else{
			let isConfirmIconExist = btn.parentElement.querySelector('.fa-check-square');
			if(isConfirmIconExist) isConfirmIconExist.parentElement.remove();
			clearEmptyInputMessage()
		}
	}

	const updateKeyData = () =>{
		const editKeyDataButtons = document.querySelectorAll('.editKeyData');
		for(const editKeyData of editKeyDataButtons){
			editKeyData.addEventListener('click', ()=>{
				toggleEditIcon(editKeyData);
				let keyDataInfo = editKeyData.parentElement.parentElement.parentElement;
				let keyDataInputs = keyDataInfo.querySelectorAll('input');
				toggleReadOnly(keyDataInputs, editKeyData);
				if(editKeyData.classList.contains('fa-edit')){
					addConfirmIconAndUpdate(editKeyData, false, keyDataInputs)
				}else{
					addConfirmIconAndUpdate(editKeyData, true, keyDataInputs)
				}
			})
		}
	}

	////
	// ADD NEW KEY DATA
	////
	const addTableLine = () =>{
		const newLine = document.createElement('tr');
		newLine.classList.add('addNewLineContent');
		newLine.innerHTML = `
			<td class="col-md-3"><input type="text" value="" name="name" name="name" class="form-control"/></td>
			<td class="col-md-3"><input type="text" value="" name="content" name="content" class=" form-control"/></td>
			<td class="col-md-1"><input type="text" value="" name="date_modification" name="date_modification" class="form-control" readonly/></td>
			<td class="text-white col-md-1">
				<div class="d-flex justify-content-around">
					<button class="addKeyData" type="submit">
						<i class="fas fa-check-square font-icon cursorPointerOn textJauneVerisure" title="Ajouter une key data"></i>
					</button>
					<button class="cancel_addKeyData">
						<i class="fas fa-window-close font-icon cursorPointerOn textDarkGreyVerisure" title="Annuler l'ajout"></i>
					</button>
				</div>
			</td>
		`;

		let keysDatasTable = document.querySelector('tbody');
		keysDatasTable.appendChild(newLine)

	}

	const cancelAddKeyData = () =>{
		const cancelBtns = document.querySelectorAll('.cancel_addKeyData');
		const btnAddKeyData = document.getElementById('addNewKeyData');

		if(cancelBtns){
			for (const cancelBtn of cancelBtns) {
				cancelBtn.addEventListener('click', ()=>{
					let lineContainer = cancelBtn.parentElement.parentElement.parentElement;
					lineContainer.remove();
					if(btnAddKeyData.classList.contains('addAnimation')) {
						btnAddKeyData.classList.remove('addAnimation');
					}
				})
			}
		}
	}

	const toggleEditKeyData = (inputs, btnsContainer) =>{
		for (const input of inputs) {
			input.setAttribute('readonly', true)
		}
		btnsContainer.remove();
	}

	const submitNewKeyData = () =>{
		let isEmptyInput = false;
		let uniqueName = false;
		const addKeyDataBtn = document.querySelector('.addKeyData');
		if(addKeyDataBtn){
			addKeyDataBtn.addEventListener('click', ()=>{
				let datas = {};
				let trEl = addKeyDataBtn.parentElement.parentElement.parentElement;
				let nameInput = trEl.querySelector('input[name="name"]');
				datas.name = nameInput.value.trim();
				datas.content = trEl.querySelector('input[name="content"]').value.trim();
				let inputsArray = [nameInput, trEl.querySelector('input[name="content"]')];
				checkEmptyInput(inputsArray, isEmptyInput);
				if(checkEmptyInput(inputsArray, isEmptyInput)){
					return;
				}else{
					const isNameUniqueReturn = async () => {
						uniqueName = await isKeyDataNameUnique(nameInput, datas, 'addKeyData');
						if(uniqueName){
							let btnsContainer = addKeyDataBtn.parentElement;
							toggleEditKeyData(inputsArray, btnsContainer);
						}
						return;
					}
					isNameUniqueReturn();
					return;
				}
			})
		}
	}

	const addKeyData = () =>{
		const btnAddKeyData = document.getElementById('addNewKeyData');
		btnAddKeyData.addEventListener('click', ()=>{
			btnAddKeyData.classList.add('addAnimation');
			let isNewLineExist = document.querySelector('.addNewLineContent');
			if(isNewLineExist) {
				return;
			}else{
				addTableLine();
			}
			cancelAddKeyData();
			submitNewKeyData();
		})
	}

	////
	// DELETE KEY DATA
	////
	const deleteKeyData = () =>{
		const deleteKeyDataButtons = document.querySelectorAll('.deleteKeyData');
		const delete_nameKeyData = document.getElementById('delete_nameKeyData');
		const delete_contentKeyData = document.getElementById('delete_contentKeyData');
		const delete_idKeyData = document.getElementById('delete_idKeyData');
		const delete_name_KeyData = document.getElementById('delete_name_KeyData');
		for (const deleteKeyData of deleteKeyDataButtons){

			deleteKeyData.addEventListener('click', ()=>{
				let nameKeyData = deleteKeyData.dataset.name;
				let idKeyData = deleteKeyData.dataset.id;
				let contentKeyData = deleteKeyData.dataset.content;

				delete_nameKeyData.innerText = nameKeyData;
				delete_contentKeyData.innerText = contentKeyData;
				delete_idKeyData.value= idKeyData;
				delete_name_KeyData.value = nameKeyData;
			})
		}
	}

	////
	// RESPONSIVE: SMALL SCREEN
	////
	const resizeTable =(screenSize) =>{
		let columnsToResize = document.querySelectorAll('.updateSize');
		if(screenSize.matches){
			for (const column of columnsToResize) {
				column.classList.add('col-md-2');
			}
		}else{
			for (const column of columnsToResize) {
				column.classList.remove('col-md-2');
			}
		}
	}
	let screenSize = window.matchMedia("(max-width: 868px)")

	addEventListener("resize", (event) => {
		resizeTable(screenSize);
	});
	resizeTable(screenSize);
	//

	// Appel des fonctions
	manageDisplayAlertInfo();
	deleteKeyData();
	updateKeyData();
	addKeyData();
});


