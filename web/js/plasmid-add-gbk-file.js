$(document).ready(function(){
    if($('input[name="plasmid[addGenBankFile]"][value=0]').prop('checked'))
    {
        $("#genBank-file-field").hide();
    }

    $('input[name$="plasmid[addGenBankFile]"]').change(function(){
        $("#genBank-file-field").toggle("slow");
    });
});
