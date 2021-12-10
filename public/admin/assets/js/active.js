function onClickBtnActive(event){
    event.preventDefault();
    const icon= this.querySelector('i');
    const  class_=this;
    const active= this.closest("tr").querySelector('.active');
    console.log(active);
    const url = this.href;
    console.log(icon.classList.value);
    $.ajax({
        url:      url,
        type:       'get',
        dataType:   'json',
        success: function(response,status){

            if(class_.classList.contains('btn-hover-success')){
                class_.classList.replace('btn-hover-success','btn-hover-danger');
            }else{
                class_.classList.replace('btn-hover-danger','btn-light-success');
            }

            /* if(class_.classList.contains('btn-light-success')){
                 class_.classList.replace('btn-light-danger','btn-light-success');
             }else{
                 class_.classList.replace('btn-light-success','btn-light-danger');
             }
 */
            if(icon.classList.contains('fa-check')){

                icon.classList.replace('fa-check','fa-ban');
                class_.classList.replace('btn-light-danger','btn-light-success');
            }
            else {

                icon.classList.replace('fa-ban','fa-check');
                class_.classList.replace('btn-light-success','btn-light-danger');
            }

            if(response.active==1){
                active.textContent="Activé";
            }else{
                active.textContent="Désactivé"
            }

        },
        error :function(error)
        {
            console.log(error);
        }
    });
}
document.querySelectorAll('a.activer').forEach(function (link) {
    link.addEventListener('click',onClickBtnActive);
})