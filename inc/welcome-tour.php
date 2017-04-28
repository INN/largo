<?php
/**
 * Theme Activation Tour
 *
 * This class handles the pointers used in the introduction tour.
 * @package Popup Demo
 * @see https://gist.github.com/DevinWalker/7595475
 *
 */

class Largo_Theme_Tour {

    private $pointer_close_id = 'largo_tourlkjsdkf'; //value can be cleared to retake tour

    /**
     * Class constructor.
     *
     * If user is on a pre pointer version bounce out.
     */
    function __construct() {
        global $wp_version;

        //pre 3.3 has no pointers
        if (version_compare($wp_version, '3.4', '<'))
            return false;

        //version is updated ::claps:: proceed
        add_action('admin_enqueue_scripts', array($this, 'enqueue'));
    }

    /**
     * Enqueue styles and scripts needed for the pointers.
     */
    function enqueue() {
        if (!current_user_can('manage_options'))
            return;

        // Assume pointer shouldn't be shown
        $enqueue_pointer_script_style = false;

        // Get array list of dismissed pointers for current user and convert it to array
        $dismissed_pointers = explode(',', get_user_meta(get_current_user_id(), 'dismissed_wp_pointers', true));

        // Check if our pointer is not among dismissed ones
        if (!in_array($this->pointer_close_id, $dismissed_pointers)) {
            $enqueue_pointer_script_style = true;

            // Add footer scripts using callback function
            add_action('admin_print_footer_scripts', array($this, 'intro_tour'));
        }

        // Enqueue pointer CSS and JS files, if needed
        if ($enqueue_pointer_script_style) {
            wp_enqueue_style('wp-pointer');
            wp_enqueue_script('wp-pointer');
        }

    }


    /**
     * Load the introduction tour
     */
    function intro_tour() {

        $adminpages = array(

            //array name is the unique ID of the screen @see: http://codex.wordpress.org/Function_Reference/get_current_screen
            'themes' => array(
                'content' => "<h3>" . __("Welcome to Largo", 'textdomain') . "</h3>"
                    . "<p>" . __("You've just installed a powerful news publishing framework for WordPress. We'd love to show you around so you can take full advantage of everything Largo can do for you. You can take this tour again from Appearence --> Theme Options", 'textdomain') . "</p>", //Content for this pointer
                'id' => 'menu-appearance', //ID of element where the pointer will point
                'position' => array(
                    'edge' => 'left', //Arrow position; change depending on where the element is located
                    'align' => 'center' //Alignment of Pointer
                ),
                'button2' => __('Tour Largo', 'textdomain'), //text for the next button
                'function' => 'window.location="' . admin_url('options-general.php?welcome_tour=1') . '";' //where to take the user
            ),
            'options-general' => array(
                'content' => '<h3>' . __('Settings', 'textdomain') . '</h3><p>' . __('Passim umentia et ponderibus colebat quinta lege norant. Omni dissaepserat imagine adsiduis nebulas densior subsidere. Exemit secuit super fuerant duae prima diverso. Regna silvas. Crescendo habentem umentia siccis quod spisso ambitae legebantur. Iners extendi opifex nunc praecipites iussit. Terram opifex perveniunt terra est. ', 'textdomain') . '</p>',
                'id' => 'menu-settings',

                'button2' => __('Next', 'textdomain'),
                'function' => 'window.location="' . admin_url('edit.php?post_type=page&welcome_tour=2') . '";'
            ),
            'edit-page' => array(
                'content' => "<h3>" . __("Appearances", 'textdomain') . "</h3>"
                    . "<p>" . __("Corpora mixta plagae manebat montibus frigore retinebat primaque obsistitur. Animus densior caelumque nova campos est pondus eodem. Mollia viseret. Prima erectos formaeque. Freta habitandae cuncta addidit opifex perveniunt nebulas gentes. Quisque vix lacusque quinta deus his. Arce in humanas illas tractu humanas pugnabant.", 'textdomain') . "</p>",
                'id' => 'menu-pages',
                'button2' => __('Next', 'textdomain'),
                'function' => 'window.location="' . admin_url('tools.php?welcome_tour=3') . '";'
            ),
            'tools' => array(
                'content' => "<h3>" . __("Tools", 'textdomain') . "</h3>"
                    . "<p>" . __("Formas cuncta aethera quicquam. Illi diremit inclusum caesa foret mixta cum speciem illic. Qui margine fratrum aer stagna pluviaque extendi quod pondere. Nitidis nix. Ripis umor. Cetera subsidere. Cingebant occiduo circumdare tractu ita aethere ante fuerant formas. Utque sole congestaque tuba fixo diu.", 'textdomain') . "</p>",
                'id' => 'menu-tools'
            ),
        );


        $page = '';
        $screen = get_current_screen();


        //Check which page the user is on
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
        }
        if (empty($page)) {
            $page = $screen->id;
        }

        $function = '';
        $button2 = '';
        $opt_arr = array();

        //Location the pointer points
        if (!empty($adminpages[$page]['id'])) {
            $id = '#' . $adminpages[$page]['id'];
        } else {
            $id = '#' . $screen->id;
        }


        //Options array for pointer used to send to JS
        if ('' != $page && in_array($page, array_keys($adminpages))) {
            $align = (is_rtl()) ? 'right' : 'left';
            $opt_arr = array(
                'content' => $adminpages[$page]['content'],
                'position' => array(
                    'edge' => (!empty($adminpages[$page]['position']['edge'])) ? $adminpages[$page]['position']['edge'] : 'left',
                    'align' => (!empty($adminpages[$page]['position']['align'])) ? $adminpages[$page]['position']['align'] : $align
                ),
                'pointerWidth' => 460
            );
            if (isset($adminpages[$page]['button2'])) {
                $button2 = (!empty($adminpages[$page]['button2'])) ? $adminpages[$page]['button2'] : __('Next', 'textdomain');
            }
            if (isset($adminpages[$page]['function'])) {
                $function = $adminpages[$page]['function'];
            }
        }

        $this->print_scripts($id, $opt_arr, __("Close", 'textdomain'), $button2, $function);
    }


    /**
     * Prints the pointer script
     *
     * @param string $selector The CSS selector the pointer is attached to.
     * @param array $options The options for the pointer.
     * @param string $button1 Text for button 1
     * @param string|bool $button2 Text for button 2 (or false to not show it, defaults to false)
     * @param string $button2_function The JavaScript function to attach to button 2
     * @param string $button1_function The JavaScript function to attach to button 1
     */
    function print_scripts($selector, $options, $button1, $button2 = false, $button2_function = '', $button1_function = '') {
        ?>
        <script type="text/javascript">
            //<![CDATA[
            (function ($) {

                var wordimpress_pointer_options = <?php echo json_encode( $options ); ?>, setup;

                //Userful info here
                wordimpress_pointer_options = $.extend(wordimpress_pointer_options, {
                    buttons: function (event, t) {
                        button = jQuery('<a id="pointer-close" style="margin-left:5px" class="button-secondary">' + '<?php echo $button1; ?>' + '</a>');
                        button.bind('click.pointer', function () {
                            t.element.pointer('close');
                        });
                        return button;
                    }
                });

                setup = function () {
                    $('<?php echo $selector; ?>').pointer(wordimpress_pointer_options).pointer('open');
                    <?php
                    if ( $button2 ) { ?>
                    jQuery('#pointer-close').after('<a id="pointer-primary" class="button-primary">' + '<?php echo $button2; ?>' + '</a>');
                    <?php } ?>
                    jQuery('#pointer-primary').click(function () {
                        <?php echo $button2_function; ?>
                    });
                    jQuery('#pointer-close').click(function () {
                        <?php if ( $button1_function == '' ) { ?>
                        $.post(ajaxurl, {
                            pointer: '<?php echo $this->pointer_close_id; ?>', // pointer ID
                            action: 'dismiss-wp-pointer'
                        });

                        <?php } else { ?>
                        <?php echo $button1_function; ?>
                        <?php } ?>
                    });

                };

                if (wordimpress_pointer_options.position && wordimpress_pointer_options.position.defer_loading) {
                    $(window).bind('load.wp-pointers', setup);
                } else {

                    $(document).ready(setup);
                }

            })(jQuery);
            //]]>
        </script>
    <?php
    }
}

$largo_theme_tour = new Largo_Theme_Tour();