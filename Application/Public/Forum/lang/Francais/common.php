<?php

// Language definitions for frequently used strings
$lang_common = array(

// Text orientation and encoding
'lang_direction'			=>	'ltr',	// ltr (Left-To-Right) or rtl (Right-To-Left)
'lang_identifier'			=>	'fr',

// Number formatting
'lang_decimal_point'	=>	'.',
'lang_thousands_sep'	=>	',',

// Notices
'Bad request'				=>	'Mauvaise requête. Le lien que vous avez suivi est incorrecte/plus valide.',
'No view'					=>	'Vous n\'avez pas les droits nécessaire pour accéder à ces forums.',
'No permission'				=>	'Vous n\'avez pas les droits nécessaire pour accéder à cette page.',
'CSRF token mismatch'		=>	'Incapacité de confirmer la connexion sécurisée. La raison peut être que trop de temps a passé entre le moment de votre entrée à cette page et l\'envoi d\'u formulaire ou clique d\'un lien. Si c\'est le cas et que vous souhaitez continuer votre action, veuillez cliquer le bouton confirmer. Sinon, veuillez cliquer sur le bouton Annuler pour retourner où vous étiez.',
'No cookie'					=>	'Vous semblez être correctement logué, cependant un cookie n\'a pas pu être paramétré. Veuillez vérifier tous vos paramètres et si possible activer les cookies pour ce site web.',


// Miscellaneous
'Forum index'				=>	'Index du Forum',
'Submit'					=>	'Envoyer',	// "name" of submit buttons
'Cancel'					=>	'Annuler', // "name" of cancel buttons
'Preview'					=>	'Prévisualisation',	// submit button to preview message
'Delete'					=>	'Supprimer',
'Split'						=>	'Séparer',
'Ban message'				=>	'Vous êtes banni de ce forum.',
'Ban message 2'				=>	'Le ban expire dans %s.',
'Ban message 3'				=>	'L\'administrateur ou le modérateur qui vous a banni vous a laissé le message suivant :',
'Ban message 4'				=>	'Veuillez addresser toutes demandes à l\'administrateur du forum à %s.',
'Never'						=>	'Jamais',
'Today'						=>	'Aujourd\'hui',
'Yesterday'					=>	'Hier',
'Forum message'				=>	'Message du forum',
'Maintenance warning'		=>	'<strong>Attention ! %s Activé.</strong> NE PAS VOUS DELOGUER car vous seriez dans l\'impossibilité de vous loguer à nouveau.',
'Maintenance mode'			=>	'Mode Maintenance',
'Redirecting'				=>	'Redirection',
'Forwarding info'			=>	'Vous devriez être redirigé automatiquement à une nouvelle page dans %s %s.',
'second'					=>	'seconde',	// singular
'seconds'					=>	'secondes',	// plural
'Click redirect'			=>	'Cliquez ici si vous ne voulez pas attendre plus longtemps (ou si votre navigateur ne vous redirige pas automatiquement)',
'Invalid e-mail'			=>	'L\'adresse e-mail que vous avez entré est invalide.',
'New posts'					=>	'Nouveaux posts',	// the link that leads to the first new post
'New posts title'			=>	'Chercher des sujets contenant des posts fait depuis votre dernière visite.',	// the popup text for new posts links
'Active topics'				=>	'Sujets actifs',
'Active topics title'		=>	'Chercher des sujets avec des posts récent.',
'Unanswered topics'			=>	'Sujets sans réponses',
'Unanswered topics title'	=>	'Chercher des sujets sans réponse.',
'Username'					=>	'Nom d\'utilisateur',
'Registered'				=>	'Enregistré',
'Write message'				=>	'Ecrire un message :',
'Forum'						=>	'Forum',
'Posts'						=>	'Posts',
'Pages'						=>	'Pages',
'Page'						=>	'Page',
'BBCode'					=>	'BBCode',	// You probably shouldn't change this
'Smilies'					=>	'Smileys',
'Images'					=>	'Images',
'You may use'				=>	'Vous pouvez utiliser : %s',
'and'						=>	'et',
'Image link'				=>	'image',	// This is displayed (i.e. <image>) instead of images when "Show images" is disabled in the profile
'wrote'						=>	'a écrit',	// For [quote]'s (e.g., User wrote:)
'Code'						=>	'Code',		// For [code]'s
'Forum mailer'				=>	'%s Mailer',	// As in "MyForums Mailer" in the signature of outgoing e-mails
'Write message legend'		=>	'Ecrivez votre poste',
'Required information'		=>	'Information requiese',
'Reqmark'					=>	'*',
'Required'					=>	'(Requis)',
'Required warn'				=>	'Tous les champs marqués %s doivent être complétés avant que le formulaire ne soit envoyé.',
'Crumb separator'			=>	' »&#160;', // The character or text that separates links in breadcrumbs
'Title separator'			=>	' - ',
'Page separator'			=>	'&#160;', //The character or text that separates page numbers
'Spacer'					=>	'…', // Ellipsis for paginate
'Paging separator'			=>	' ', //The character or text that separates page numbers for page navigation generally
'Previous'					=>	'Précédent',
'Next'						=>	'Suivant',
'Cancel redirect'			=>	'Operation annulée. Redirection …',
'No confirm redirect'		=>	'Aucune confirmation fournie. Operation annulée. Redirection …',
'Please confirm'			=>	'Confirmer svp :',
'Help page'					=>	'Aide pour : %s',
'Re'						=>	'Re :',
'Page info'					=>	'(Page %1$s of %2$s)',
'Item info single'			=>	'%s [ %s ]',
'Item info plural'			=>	'%s [ %s to %s of %s ]', // e.g. Topics [ 10 to 20 of 30 ]
'Info separator'			=>	' ', // e.g. 1 Page | 10 Topics
'Powered by'				=>	'Powered by <strong>%s</strong>',
'Maintenance'				=>	'Maintenance',

// CSRF confirmation form
'Confirm'					=>	'Confirmer',	// Button
'Confirm action'			=>	'Confirmer action',
'Confirm action head'		=>	'Veuillez confirmer ou annuler votre dernière action',

// Title
'Title'						=>	'Titre',
'Member'					=>	'Membre',	// Default title
'Moderator'					=>	'Modérateur',
'Administrator'				=>	'Administrateur',
'Banned'					=>	'Banni',
'Guest'						=>	'Invité',

// Stuff for include/parser.php
'BBCode error 1'			=>	'[/%1$s] a été trouvé sans résultats [%1$s]',
'BBCode error 2'			=>	'tag [%s] vide',
'BBCode error 3'			=>	'[%1$s] a été ouvert durant [%2$s], non autorisé',
'BBCode error 4'			=>	'[%s] a été ouvert dans lui-même, non autorisé',
'BBCode error 5'			=>	'[%1$s] a été trouvé sans résultats[/%1$s]',
'BBCode error 6'			=>	'tag [%s] contient une section attribut vide',
'BBCode nested list'		=>	'[list] tags ne peut être incluse',
'BBCode code problem'		=>	'Il y a un problème avec votre tag [code]',

// Stuff for the navigator (top of every page)
'Index'						=>	'Index',
'User list'					=>	'Liste d\'utilisateurs',
'Rules'						=>  'Rules',
'Search'					=>  'Recherche',
'Register'					=>  'S\'enregistrer',
'register'					=>	'enregistrer',
'Login'						=>  'Login',
'login'						=>	'loguer',
'Not logged in'				=>  'Vous n\'êtes pas logué.',
'Profile'					=>	'Profil',
'Logout'					=>	'Logout',
'Logged in as'				=>	'Logué en tant que %s.',
'Admin'						=>	'Administration',
'Last visit'				=>	'Dernière visite %s',
'Mark all as read'			=>	'Marquer tous les sujets comme lu',
'Login nag'					=>	'Veuillez vous loguer ou vous enregistrer.',
'New reports'				=>	'Nouveaux reports',

// Alerts
'New alerts'				=>	'Nouvelles Alertes',
'Maintenance alert'			=>	'<strong>Attention ! Mode maintenance activé</strong> Ce tableau est actuellement en maintenantce. <em>NE PAS</em> se déloguer, si vous le faites, vous ne serez pas en mesure de vous loguer à nouveau.',
'Updates'					=>	'mise à jour PunBB',
'Updates failed'			=>	'The latest attempt at checking for updates against the punbb.informer.com updates service failed. This probably just means that the service is temporarily overloaded or out of order. However, if this alert does not disappear within a day or two, you should disable the automatic check for updates and check for updates manually in the future.',
'Updates version n hf'		=>	'A newer version of PunBB, version %s, is available for download at <a href="http://punbb.informer.com/">punbb.informer.com</a>. Furthermore, one or more hotfix extensions are available for install on the Extensions tab of the admin interface.',
'Updates version'			=>	'A newer version of PunBB, version %s, is available for download at <a href="http://punbb.informer.com/">punbb.informer.com</a>.',
'Updates hf'				=>	'One or more hotfix extensions are available for install on the Extensions tab of the admin interface.',
'Database mismatch'			=>	'Database version mismatch',
'Database mismatch alert'	=>	'Your PunBB database is meant to be used in conjunction with a newer version of the PunBB code. This mismatch can lead to your forum not working properly. It is suggested that you upgrade your forum to the newest version of PunBB.',

// Stuff for Jump Menu
'Go'						=>	'Go',		// submit button in forum jump
'Jump to'					=>	'Jump to forum:',

// For extern.php RSS feed
'ATOM Feed'					=>	'Atom',
'RSS Feed'					=>	'RSS',
'RSS description'			=>	'Le sujet le plus plus récent sur %s.',
'RSS description topic'		=>	'Le post le plus récent sur %s.',
'RSS reply'					=>	'Re : ',	// The topic subject will be appended to this string (to signify a reply)

// Accessibility
'Skip to content'					=>	'Skip to forum content',

// Debug information
'Querytime'						=>	'Généré en %1$s secondes, %2$s requêtes exécutées',
'Debug table'						=>	'Debug information',
'Debug summary'						=>	'Database query performance information',
'Query times'						=>	'Time (s)',
'Query'							=>	'Query',
'Total query time'					=>	'Total query time',

);
