<?php
/**
 *
 * Class used as base to create PDF
 *
 * @package   doccure
 * @author    Dreams Technologies
 * @link      https://themeforest.net/user/dreamstechnologies/portfolio
 * @since 1.0
 */
require doccureGlobalSettings::get_plugin_path() . 'libraries/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;
if (!class_exists('doccure_Prepare_Pdf')) {

    class doccure_Prepare_Pdf {

        function __construct() {
			add_action('init', array(&$this, 'doccure_do_pdf_action'));
        }

		/**
         * @pdf download
         * @return {}
         * @author Dreams Technologies
         */

		public function doccure_do_pdf_action() {
			if( !empty($_POST['pdf_booking_id']) ){
				global $doccure_options;
				ob_clean();
				$booknig_id	= !empty($_POST['pdf_booking_id']) ? intval($_POST['pdf_booking_id']) : '';
				$appointment_prefix	= !empty($doccure_options['appointment_prefix']) ? $doccure_options['appointment_prefix'] : 'appointment-';
				$html		= apply_filters('doccure_pdf',$booknig_id);
				ob_start();
				$dompdf 	= new Dompdf();
				$dompdf->loadHtml($html);
				$dompdf->set_option('isHtml5ParserEnabled', false);
				$dompdf->setPaper('A4', 'portrait');
				$dompdf->render();
				$dompdf->stream($appointment_prefix.$booknig_id.".pdf");
				///$dompdf->stream('newfile',array('Attachment'=>0));
				echo ob_get_clean();
			}
		}
        
	}
   new doccure_Prepare_Pdf();
}
