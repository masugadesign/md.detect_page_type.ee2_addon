<?php
/*
===============================================================================
File: pi.md_detect_page_type.php (EE2 version)
Thread: http://expressionengine.com/forums/viewthread/92307/
Docs: http://www.masugadesign.com/the-lab/scripts/md-detect-page-type/
Misc Related Links:
http://expressionengine.com/forums/viewthread/55700/P18/
-------------------------------------------------------------------------------
Purpose: Detect if the page you are on is a pagination, category, or yearly 
         archive page.
===============================================================================
*/
$plugin_info = array(
    'pi_name'        => 'MD Detect Page Type',
    'pi_version'     => '1.0.2',
    'pi_author'      => 'Ryan Masuga',
    'pi_author_url'  => 'http://masugadesign.com/',
    'pi_description' => 'Detect if the page you are on is a pagination, category, or yearly archive page.',
    'pi_usage'       => Md_detect_page_type::usage()
  );

class Md_detect_page_type {

var $return_data = "";
  
  function Md_detect_page_type()
  {
    $this->EE =& get_instance();

    $tagdata = $this->EE->TMPL->tagdata;
    $conds = array();
    $category_word = $this->EE->config->item("reserved_category_word");

    if ($this->EE->TMPL->fetch_param('url_segment') !== FALSE)
    {
      $url_segment = $this->EE->TMPL->fetch_param('url_segment');
    }
    else
    {
      $url_segment = end($this->EE->uri->segments);
    }
      
    $conds['pagination_page'] = (preg_match('/^[P][0-9]+$/i', $url_segment)) ? TRUE : FALSE;
    $conds['category_page'] = (preg_match("/$category_word/", $url_segment)) ? TRUE : FALSE;
    $conds['yearly_archive_page'] = (preg_match("/^\d{4}$/", $url_segment)) ? TRUE : FALSE;

    // Prep output
    $tagdata = $this->EE->functions->prep_conditionals($tagdata, $conds);

    // return
    $this->return_data = $tagdata;

  }
    
// ----------------------------------------
//  Plugin Usage
// ----------------------------------------

// This function describes how the plugin is used.
//  Make sure and use output buffering

function usage()
{
ob_start(); 
?>
Useful if you're tying to use a single template to do paginated entries, categories and a single-entry. May have other uses - get creative!

PARAMETERS: 
The tag has one parameter:

1. url_segment - The segment to check. [REQUIRED]

Example usage:
{exp:md_detect_page_type url_segment="{segment_3}"}
Pagination Page: {if pagination_page}This is a Paginated Page{/if}<br />
Category Page: {if category_page}This is a Category Page{/if}<br />
Yearly Archive Page: {if yearly_archive_page}This is a Yearly Archive Page{/if}
{/exp:md_detect_page_type}

<?php
$buffer = ob_get_contents();
  
ob_end_clean(); 

return $buffer;
}
// END

}
?>