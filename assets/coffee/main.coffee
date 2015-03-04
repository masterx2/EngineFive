$(->
  $('.post .date').each((i,v)->
    f_date = moment(parseInt($(v).text())*1e3).format('LLLL')
    $(v).text(f_date)
  )

  tinymce.init({
    selector: "textarea"
    plugins: [
      "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
      "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
      "save table contextmenu directionality emoticons template paste textcolor"
    ]
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons"
  });
)