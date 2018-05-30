<?php
/* ajoute une page d' options pour le plugin:
le titre qui va apparaître sur la page de l' image (image.php)
les textes à afficher sur la page image
ce qui doit se trouver dans la balise "alt"
nombre de photos à afficher sur la page d' archive
la taille du post thumbnail
la taille des imagettes dans les galerie.



d' autres possibilités:
comment, lors du téléchargement peupler la légende de la photo? le tire de la photo? le texte alternatif? la description?, en sachant que les choix de wordpress ne correspondent pas nécessairement à ceux en usage dans le milieu de la photo






le code provient du site wp-generate, mais il y a des erreurs, donc j' ai commenté le "add_action"


 */

	 function add_admin_menu()
	 {

		add_menu_page(
			esc_html__( 'Hierarchical Keywords settings', 'text_domain' ),
			esc_html__( 'Hierarchical Keywords general settings', 'text_domain' ),
			'manage_options',
			'Hierarchical_Keywords',
			'page_layout' ,
			HIERARCHICAL_KEYWORDS_URL . 'images/photo-icon.png',
			10
		);

	}
	add_action( 'admin_menu',  'add_admin_menu' ) ;



	function init_settings()
	{
		register_setting(
			'general_settings',
			'Hierarchical_Keywords'
		);

		add_settings_section(
			'Hierarchical_Keywords_section',
			'',
			false,
			'Hierarchical_Keywords'
		);


		/*Photo title */
		add_settings_field(
			'photo_title',
			__( 'Photo title', 'text_domain' ),
			 'render_photo_title_field',
			'Hierarchical_Keywords',
			'Hierarchical_Keywords_section'
		);

		/* image texts to display */
		add_settings_field(
			'img_txts',
			__( 'image texts to display', 'text_domain' ),
			'render_img_datas_field' ,
			'Hierarchical_Keywords',
			'Hierarchical_Keywords_section',
			array( 'label_for' => 'img_txts' )
		);

		/* display in the tag alt */
		add_settings_field(
			'alt_balise',
			__( 'display in the tag "alt"', 'text_domain' ),
			 'render_alt_balise_field',
			'Hierarchical_Keywords',
			'Hierarchical_Keywords_section'
		);

		/* the photographer name */
		add_settings_field(
			'photographer_name',
			__( 'If photographer is checked, the photographer name:', 'text_domain' ),
			'render_photographer_name_field' ,
			'Hierarchical_Keywords',
			'Hierarchical_Keywords_section'
		);

		/* Photos number on the archive page */
		add_settings_field(
			'nbre_photos_archive',
			__( 'Photos number on the archive page', 'text_domain' ),
			 'render_nbre_photos_archive_field' ,
			'Hierarchical_Keywords',
			'Hierarchical_Keywords_section'
		);

		/* post thumbnail size */
		add_settings_field(
			'post_thumb_size',
			__( 'post thumbnail size', 'text_domain' ),
			 'render_post_thumb_size_field' ,
			'Hierarchical_Keywords',
			'Hierarchical_Keywords_section'
		);

		/* galleries thumbnails size */
		add_settings_field(
			'galleries_thumb_size',
			__( 'galleries thumbnails size', 'text_domain' ),
			 'render_galleries_thumb_size_field' ,
			'Hierarchical_Keywords',
			'Hierarchical_Keywords_section'
		);



	}
//	add_action( 'admin_init',  'init_settings'  );


	function page_layout()
	{
		// Check required user capability
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'text_domain' ) );
		}

		?>
		<!-- Admin Page Layout -->
		<div class="wrap">

			<?php echo '	<h1>' . get_admin_page_title() . '</h1>' . "\n"; ?> <!-- ne s' affiche pas sous la forme <h1><?php get_admin_page_title()?></h1> -->

			<form action="options.php" method="post">
				<?php
				settings_fields( 'general_settings' );
				do_settings_sections( 'Hierarchical_Keywords' );
				submit_button();
				?>
			</form>
		</div>
		<?php

	}


	/* Photo title	 */
	function render_photo_title_field()
	{
		// Retrieve data from the database.
		$options = get_option( 'Hierarchical_Keywords' );

		// Set default value.
		$value = isset( $options['photo_title'] ) ? $options['photo_title'] : '';

		// Field output.
		?>
		<select name="Hierarchical_Keywords[photo_title]" class="photo_title_field">

			<option value="post title" <?php selected( $value, 'post title', true )?> > <?php echo  _e( 'post title', 'text_domain' )?> </option>

			<option value="file name" <?php selected( $value, 'file name', true )?> >  <?php echo  _e( 'file name', 'text_domain' )?> </option>

			<option value="iptc title" <?php selected( $value, 'iptc title', true ) ?> > <?php echo  _e( 'iptc title', 'text_domain' ) ?></option>

		</select>

		<p class="description"> <?php echo  _e( 'The title to display on the image page', 'text_domain' ) ?></p>
		<?php

	}


	/* image texts to display */
	function render_img_datas_field()
	{
		// Retrieve data from the database.
		$options = get_option( 'Hierarchical_Keywords' );

		// Set default value.
		$value = isset( $options['img_datas'] ) ? $options['img_datas'] : null;

		//$value =  $options['img_txts'];

		// Field output.
		?>
		<input type="checkbox" name="Legend" class="img_datas_field" value= "<?php $value['Legend'] ?>" <?php checked( $value['Legend'], 'Legend', true )?> > <?php  _e( 'Legend', 'text_domain' ) ?> <br>

		<input type="checkbox" name="headline" class="img_datas_field" value= "<?php $value['headline'] ?>" <?php checked( $value['headline'], 'headline', true ) ?> > <?php  _e( 'Headline', 'text_domain' ) ?> <br>

		<input type="checkbox" name="description" class="img_datas_field" value= "<?php $value['description'] ?>" <?php checked( $value['description'], 'description', true ) ?> > <?php  _e( 'Description', 'text_domain' ) ?> <br>

		<input type="checkbox" name="Country" class="img_datas_field" value= "<?php $value['Country']?>"  <?php checked( $value['Country'], 'Country', true ) ?> > <?php  _e( 'Country', 'text_domain' ) ?> <br>

		<input type="checkbox" name="state" class="img_datas_field" value= "<?php $value['state'] ?>" <?php checked( $value['state'], 'state', true ) ?> > <?php  _e( 'State', 'text_domain' ) ?> <br>

		<input type="checkbox" name="city" class="img_datas_field" value= "<?php $value['city'] ?>" <?php checked( $value['city'], 'city', true )?> > <?php  _e( 'City', 'text_domain' )?> <br>

		<input type="checkbox" name="location" class="img_datas_field" value= "<?php $value['location'] ?>" <?php checked( $value['location'], 'location', true )?> > <?php  _e( 'Location', 'text_domain' ) ?> <br>

		<input type="checkbox" name="google_map" class="img_datas_field" value= "<?php $value['google_map'] ?>" <?php checked( $value['google_map'], 'google_map', true ) ?> > <?php  _e( 'google map', 'text_domain' )?> <br>

		<input type="checkbox" name="creation_date" class="img_datas_field" value= "<?php $value['creation_date'] ?>" <?php checked( $value['creation_date'], 'creation_date', true ) ?> >  <?php  _e( 'creation date', 'text_domain' ) ?> <br>

		<input type="checkbox" name="copyright" class="img_datas_field" value= "<?php $value['copyright'] ?>" <?php checked( $value['copyright'], 'copyright', true )?> > <?php  _e( 'copyright', 'text_domain' ) ?> <br>

		<p class="description"><?php  _e( 'What to display on the image page?', 'text_domain' ) ?></p>
		<?php

	}






	/* display in the tag alt */
	function render_alt_balise_field()
	{
		// Retrieve data from the database.
		$options = get_option( 'Hierarchical_Keywords' );

		// Set default value.
		$value = isset( $options['alt_balise'] ) ? $options['alt_balise'] : null;

		// Field output.
		?>
		<input type="checkbox" name="title" class="alt_balise_field" value="1"  <?php checked( $value['title'], 1 ) ?> > <?php   _e( 'titre', 'text_domain' ) ?> </br>

		<input type="checkbox" name="legend" class="alt_balise_field" value="1" <?php checked( $value['legend'], 1) ?> > <?php   _e( 'legend', 'text_domain' ) ?> </br>

		<input type="checkbox" name="libelle" class="alt_balise_field" value="1" <?php checked( $value['libelle'], 1 ) ?> > <?php   _e( 'libellé', 'text_domain' ) ?> </br>

		<input type="checkbox" name="photographer" class="alt_balise_field" value="1" <?php checked( $value['photographer'], 1 ) ?> > <?php   _e( 'photographer', 'text_domain' ) ?> </br>

		<input type="checkbox" name="website_name" class="alt_balise_field" value="1" <?php checked( $value['website_name'], 1) ?> > <?php   _e( 'website name', 'text_domain' ) ?> </br>

		<input type="checkbox" name="copyright_photo" class="alt_balise_field" value="1" <?php checked( $value['copyright_photo'], 1 ) ?> > <?php   _e( 'photo copyright', 'text_domain' ) ?></br>



<!--
CODEX:
		<input type="checkbox" name="options[postlink]" value="1" <?php checked( $options['postlink'], 1 ); ?> />
-->



		<p class="description"> <?php echo  _e( 'how to populate the alt balise', 'text_domain' ) ?></p>
		<?php


	}


	/* the photographer name */
	function render_photographer_name_field()
	{
		// Retrieve data from the database.
		$options = get_option( 'Hierarchical_Keywords' );

		// Set default value.
		$value = isset( $options['photographer_name'] ) ? $options['photographer_name'] : '';

		// Field output.
		?>
		<input type="text" name="Hierarchical_Keywords[photographer_name]" class="regular-text photographer_name_field" placeholder="<?php esc_attr( _e( 'Photographer name', 'text_domain' )) ?>" value="<?php echo esc_attr( $value ) ?>">

		<p class="description"><?php  _e( 'the photographer name to display on the image page and articles, if it is a single author site', 'text_domain' ) ?></p>
		<?php

	}






	/* Photos number on the archive page */
	function render_nbre_photos_archive_field()
	{
		// Retrieve data from the database.
		$options = get_option( 'Hierarchical_Keywords' );

		// Set default value.
		$value = isset( $options['nbre_photos_archive'] ) ? $options['nbre_photos_archive'] : '50';

		// Field output.
		?>
		<input type="number" name="Hierarchical_Keywords[nbre_photos_archive]" class="regular-text nbre_photos_archive_field" placeholder="<?php esc_attr (_e( '50', 'text_domain' )) ?>" value="<?php echo esc_attr( $value ) ?>">

		<p class="description"> <?php  _e( 'Number of photos to display on the archive page', 'text_domain' ) ?></p>
		<?php
	}










	/* post thumbnail size */
	function render_post_thumb_size_field()
	{
		// Retrieve data from the database.
		$options = get_option( 'Hierarchical_Keywords' );

		// Set default value.
		$value = isset( $options['post_thumb_size'] ) ? $options['post_thumb_size'] : null;

		// Field output.
		?>
		<input type="radio" name="post_thumb_size" class="post_thumb_size_field" value= "<?php $value['150px'] ?>" <?php checked( $value['150px'], '150px', true ) ?> > <?php  _e( '150px', 'text_domain' )?> <br>

		<input type="radio" name="post_thumb_size" class="post_thumb_size_field" value= "<?php $value['200px'] ?>" <?php checked( $value['200px'], '200px', true ) ?> > <?php  _e( '200px', 'text_domain' ) ?> <br>

		<input type="radio" name="post_thumb_size" class="post_thumb_size_field" value= "<?php $value['250px'] ?>" <?php checked( $value['250px'], '250px', true ) ?> > <?php  _e( '250px', 'text_domain' ) ?> <br>

		<input type="radio" name="post_thumb_size" class="post_thumb_size_field" value= "<?php $value['300px'] ?>" <?php checked( $value['300px'], '300px', true ) ?> > <?php  _e( '300px', 'text_domain' ) ?> <br>

		<p class="description"><?php  _e( 'the size of the post thumbnails', 'text_domain' ) ?></p>
		<?php

	}


	/* galleries thumbnails size */
	function render_galleries_thumb_size_field()
	{
		// Retrieve data from the database.
		$options = get_option( 'Hierarchical_Keywords' );

		// Set default value.
		$value = isset( $options['galleries_thumb_size'] ) ? $options['galleries_thumb_size'] : null;

		// Field output.
		?>
		<input type="radio" name="galleries_thumb_size" class="galleries_thumb_size_field" value= "<?php $value['150px'] ?>" <?php checked( $value['150px'], '150px', true ) ?> > <?php   _e( '150px', 'text_domain' ) ?> <br>

		<input type="radio" name="galleries_thumb_size" class="galleries_thumb_size_field" value= "<?php $value['200px'] ?>" <?php  checked( $value['200px'], '200px', true ) ?> > <?php   _e( '200px', 'text_domain' ) ?> <br>

		<input type="radio" name="galleries_thumb_size" class="galleries_thumb_size_field" value= "<?php $value['250px'] ?>" <?php  checked( $value['250px'], '250px', true ) ?> > <?php  _e( '250px', 'text_domain' ) ?> <br>

		<input type="radio" name="galleries_thumb_size" class="galleries_thumb_size_field" value= "<?php $value['300px'] ?>" <?php  checked( $value['300px'], '300px', true ) ?> > <?php   _e( '300px', 'text_domain' ) ?> <br>

		<p class="description"><?php  _e( 'the size of the thumbnails of the galleries', 'text_domain' ) ?></p>
		<?php


/* comme on peu voir avec le vardump, les checkbox et radio ne fonctionnent pas. Sauf une, elles sont toutes codées comme suivant le générateur https://generatewp.com/snippet/kdn7ng3/ */
$options = get_option( 'Hierarchical_Keywords' );

?>
<div class="vardump">
<h1>$options</h1>
<pre>
<?php var_dump($options);  ?>
</pre>
</div>
<?php










	}


















?>