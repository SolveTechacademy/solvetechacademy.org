<style>

:root{

    --primary:#0d6efd;
    --secondary:#6c757d;
    --success:#198754;
    --danger:#dc3545;
    --warning:#ffc107;
    --info:#0dcaf0;
    --light:#f8f9fa;
    --dark:#212529;

    --sidebar-width:260px;

    --background:#f4f7fb;

    --card-radius:12px;

}

*{

    margin:0;
    padding:0;
    box-sizing:border-box;

}

body{

    background:var(--background);
    font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;

}

/*=============================
Sidebar
=============================*/

.sidebar{

    position:fixed;

    top:0;

    left:0;

    width:var(--sidebar-width);

    height:100vh;

    background:#ffffff;

    box-shadow:0 0 20px rgba(0,0,0,.08);

    overflow-y:auto;

    z-index:1050;

}

.sidebar-brand{

    padding:20px;

    text-align:center;

    border-bottom:1px solid #eee;

}

.sidebar-brand img{

    width:70px;

}

.sidebar-brand h5{

    margin-top:10px;

    font-weight:bold;

}

.sidebar .nav-link{

    color:#444;

    padding:14px 20px;

    transition:.3s;

    font-weight:500;

}

.sidebar .nav-link:hover{

    background:#eef4ff;

    color:var(--primary);

}

.sidebar .nav-link.active{

    background:var(--primary);

    color:#fff;

}

/*=============================
Content
=============================*/

.content{

    margin-left:var(--sidebar-width);

    padding:20px;

}

/*=============================
Topbar
=============================*/

.topbar{

    background:#fff;

    border-radius:10px;

    padding:12px 20px;

    box-shadow:0 3px 12px rgba(0,0,0,.05);

    margin-bottom:25px;

}

.topbar .student-name{

    font-weight:600;

}

/*=============================
Cards
=============================*/

.card{

    border:none;

    border-radius:var(--card-radius);

    box-shadow:0 4px 15px rgba(0,0,0,.06);

}

.card-header{

    border:none;

    font-weight:bold;

}

/*=============================
Progress
=============================*/

.progress{

    height:10px;

    border-radius:20px;

}

.progress-bar{

    border-radius:20px;

}

/*=============================
Buttons
=============================*/

.btn{

    border-radius:8px;

}

/*=============================
Tables
=============================*/

.table{

    vertical-align:middle;

}

/*=============================
Mobile
=============================*/

.mobile-toggle{

    display:none;

}

@media(max-width:992px){

.sidebar{

    left:-260px;

    transition:.3s;

}

.sidebar.show{

    left:0;

}

.content{

    margin-left:0;

}

.mobile-toggle{

    display:inline-block;

}

}

</style>