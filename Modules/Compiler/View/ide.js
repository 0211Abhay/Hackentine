let editor;

window.onload = function() {
    editor = ace.edit("editor");
    editor.setTheme("ace/theme/xcode");
    editor.session.setMode("ace/mode/c_cpp");
}

function changeLanguage() {

    let language = $("#languages").val();

    if(language == 'c')editor.session.setMode("ace/mode/c_cpp");
    else if(language == 'cpp')editor.session.setMode("ace/mode/c_cpp");
    else if(language == 'java')editor.session.setMode("ace/mode/java");
    else if(language == 'php')editor.session.setMode("ace/mode/php");
    else if(language == 'py')editor.session.setMode("ace/mode/python");
    else if(language == 'javascript')editor.session.setMode("ace/mode/javascript");
}

function executeCode() {

    $.ajax({

        url: "http://10.80.15.199/Hackentine/Modules/Compiler/Services/compiler.php",

        method: "POST",

        data: {
            language: $("#languages").val(),
            code: editor.getSession().getValue()
        },

        success: function(response) {
            $("#output").text(response)
        }
    })
}



