# UTILISATION DU FORMULAIRE - HELPER FORM -

## ETAPES DE CREATION D'UN FORMULAIRE

-1 : Créer une variable pour appeler la classe "CreateForm();" : 
    expl **$myFormPerso = new CreateForm()**  ;
-2 : Appeler la méthode **openFrom()** pour initaliser le formulaire 
-3 : Appeler les méthodes **input()/checkbox()/select()/submit()** pour ajouter les champs nécessaires au formulaire
-4 : Appeler en dernier la méthode **closeFrom()** suivit d'un **;** pour finir l'instruction et permettre l'affichage du formulaire. 
Voir exemple de form à la fin de cette notice. 

## DESCRIPTION DES PARAMETRES DES METHODES INPUT() / CHECKBOX() / SELECT() / SUBMIT()

**->input()** :
input($type=type d'input, $fieldName = nom du champs, $attributes = [tableau bi-dimensionel des attributs que l'on souhaite ajouter à l'input expl class/required,length ....] )

**->checkbox()** :
checkbox($fieldName = nom du champs, $label= label à ajouter à la checkbox, $attributes = [tableau bi-dimensionel des attributs que l'on souhaite ajouter à l'input expl class/required,length ....] )

**->select()** :
select($fieldName = nom du champs, $attributes = [tableau bi-dimensionel des attributs que l'on souhaite ajouter à l'input expl class/required,length ....], $options = [tableau bi-dimensionel des options à afficher] )

 **->submit()** :
submit($label = text à afficher dans le bouton, $attributes = [tableau bi-dimensionel des attributs que l'on souhaite ajouter à l'input expl class/required,length ....] )

## DESCRIPTIONDES METHODES OPENFORM() / ADDHTMLCONTENT() / CLOSEFORM()

**->openForm()** :
openForm($formClassParam) : la méthode accèpte 1 paramètre optionnel permettant d'ajouter une classe au formulaire.

**->addHTMLcontent()** :
addHTMLcontent("<p class='classPersonnalisee'>contenu html à ajouter</p>") : méthode pour ajouter du contenu HTML.
RQ: respecter l'ordre des parenthèse, ouvrir avec les doubles guillements et utiliser les guillemets simples à l'intérieur.

**->closeForm()** :
closeForm: la méthode accepte 2 paramètres optionnels pour retirer 2 inputs hidden présents par défaut : input id_verisure et input id_covea.
closeForm($idEnseigneInput=true/false)

## FIELDSNAMES UTILISABLES DANS LE HELPER FORM

**/!\ Si un filedName n'existe pas, merci de vous rapprocher des équipes DEV /!\ .** 
'nom_prenom',
'nom_filleul',
'nom_parrain',
'numeroClient_parrain',
'telephone',
'email',
'numero_client',
'adresse',
'ville',
'code_postal',
'contratEnseigne',
'natureIntermediaire',
'horaires'

## EXEMPLE DE FORM

<?php $myForm = new CreateForm() ; 
$myForm->openForm()
        ->input("text", "nom_prenom",["placeholder"=> "Prénom et Nom du client", "minlength"=>"2", "maxlength"=>"100", "class"=>"input", "required"=>"true", "autocomplete"=>"on"])
        ->input("text", "code_postal",["placeholder"=> "Code postal du client", "maxlength"=>"5", "class"=>"input", "required"=>"true", "autocomplete"=>"on"])
        ->input("tel", "telephone", ["placeholder"=> "Numéro de mobile du client", "maxlength"=>"10", "class"=>"input", "required"=>"true", "autocomplete"=>"on"])
        ->input("mail", "email",["placeholder"=>"VOTRE E-MAIL*"])
        ->input("text", "numero_client", ["placeholder"=> "Numéro client Enseigne", "maxlength"=>"9", "class"=>"input", "required"=>"true"])
        ->input("text", "adresse", ["placeholder"=> "Votre adresse", "minlength"=> "2", "maxlength"=>"150", "class"=>"input", "required"=>"true", "autocomplete"=>"on"])
        ->input("text","ville",["placeholder"=> "Votre ville", "maxlength"=>"150", "class"=>"input", "required"=>"true", "autocomplete"=>"on"])
        ->select("horaires", null,["0"=> "- Votre plage de rappel -", "09h00-10h00"=>"09h00 - 10h00", "10h00-11h00"=>"10h00 - 11h00", "11h00-12h00"=>"11h00 - 12h00", "12h00-13h00"=>"12h00 - 13h00","13h00-14h00"=>"13h00 - 14h00", "14h00-15h00"=>"14h00 - 15h00", "15h00-16h00"=>"15h00 - 16h00", "16h00-17h00"=>"16h00 - 17h00","17h00-18h00"=>"17h00 - 18h00","18h00-19h00" =>"18h00 - 19h00"])
        ->checkbox("contratMRH", "Etes-vous titulaire d'un contrat Assurance Habitation Enseigne", null)
        ->addHTMLcontent("<div class='test'>Je crée une div dans mon form</div>")
        ->submit('Je prends rendez-vous', ["class" =>"submit"])
        ->closeForm();
?>