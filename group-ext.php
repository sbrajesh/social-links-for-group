<?php
//handle everything except the front end display

class BP_Group_Social_Links extends BP_Group_Extension {
var $visibility = 'private'; // 'public' will show your extension to non-group members, 'private' means you have to be a member of the group to view your extension.

var $enable_create_step = true; // enable create step
var $enable_nav_item = false; //do not show in front end
var $enable_edit_item = true; // If your extensi
	function __construct() {
		$this->name = __('Social Links','bp-groups-social-links');
		$this->slug = 'social-links';

		$this->create_step_position = 23;
		$this->nav_item_position = 34;
	}
//on group crate step
	function create_screen() {
		if ( !bp_is_group_creation_step( $this->slug ) )
			return false;
		
                $this->render_form();//render the form
		wp_nonce_field( 'groups_create_save_' . $this->slug );
	}
//on group create save
	function create_screen_save() {
		global $bp;
                
		check_admin_referer( 'groups_create_save_' . $this->slug );
                if(empty($_POST['fb_link']))
                    return;
                $group_id=$bp->groups->new_group_id;
                
		if ( !groups_update_groupmeta($group_id, 'fb_link', sanitize_url($_POST['fb_link'])) ) {
				bp_core_add_message( __( 'There was an error updating the link, please try again.', 'bp-groups-social-links' ), 'error' );
			} else {
				bp_core_add_message( __( 'Link saved.', 'bp-groups-social-links' ) );
                }
	}

	function edit_screen() {
		if ( !bp_is_group_admin_screen( $this->slug ) )
			return false; ?>

                    <h2><?php echo esc_attr( $this->name ) ?></h2>
                    
                     <?php $this->render_form();?>
                   
                    <?php 	wp_nonce_field( 'groups_edit_save_' . $this->slug );?>
                    <p><input type="submit" value="<?php _e( 'Save Changes', 'bp-groups-social-links' ) ?> &rarr;" id="save" name="save" /></p>
                <?php
	}

	function edit_screen_save() {
		global $bp;

		if ( !isset( $_POST['save'] ) )
			return false;
                 if(empty($_POST['fb_link']))
                    return;
		check_admin_referer( 'groups_edit_save_' . $this->slug );


                $group_id=$bp->groups->current_group->id;
                
		if ( !groups_update_groupmeta($group_id, 'fb_link', sanitize_url($_POST['fb_link'])) ) {
				bp_core_add_message( __( 'There was an error updating the link, please try again.', 'bp-groups-social-links' ), 'error' );
			} else {
				bp_core_add_message( __( 'Link saved.', 'bp-groups-social-links' ) );
                }
                
		bp_core_redirect( bp_get_group_permalink( $bp->groups->current_group ) . '/admin/' . $this->slug );
	}

	function display() {
		/* Use this function to display the actual content of your group extension when the nav item is selected */
	}

	function widget_display() {
            
	}
        
      
        
        function render_form(){?>
             <fieldset class="group-soacial-link">

                <legend>
                        <?php _e( 'Facebook Page', 'bp-groups-social-links' ); ?>
                </legend>
                <p>
                    <input type="text" name="fb_link" id="group_fb_link" value="<?php echo group_get_fb_link();?>"/>
                </p>

            </fieldset>
        <?php
}
}
//register extension
bp_register_group_extension( 'BP_Group_Social_Links' );

function group_get_fb_link($group_id=false){
    $group=false;
    if(!$group_id){
        $group=groups_get_current_group ();
        $group_id=$group->id;
    }
    
    if(empty($group_id))
        return '';
    return groups_get_groupmeta($group_id, 'fb_link');
}
?>