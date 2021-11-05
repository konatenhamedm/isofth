import './styles/app.scss';
import  'datatables.net'
import  'datatables.net-bs4'
import 'datatables.net-responsive'
import 'datatables.net-responsive-bs4'
/*import 'datatables.net-responsive-dt'*/
/*
import 'datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js'
*/


$('#datatable').DataTable({
    "language": {
        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/French.json"
    },
    "responsive": {
        "details": "false"
    }
});