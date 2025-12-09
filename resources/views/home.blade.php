<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patria Coffee Beans</title>
    <!-- استدعاء Bootstrap للتصميم الجاهز -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; }
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1497935586351-b67a49e012bf?ixlib=rb-1.2.1&auto=format&fit=crop&w=1351&q=80');
            background-size: cover;
            background-position: center;
            height: 80vh;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .btn-coffee { background-color: #6f4e37; color: white; border: none; padding: 10px 30px; }
        .btn-coffee:hover { background-color: #5a3e2b; color: white; }
    </style>
</head>
<body>

    <!-- القائمة العلوية (Navbar) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">☕ Patria Coffee</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#">الرئيسية</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">منتجاتنا</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">عن باتريا</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">تواصل معنا</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- الجزء الرئيسي (Hero Section) -->
    <header class="hero-section">
        <div>
            <h1 class="display-3 fw-bold">قهوة باتريا الأصلية</h1>
            <p class="lead fs-4">طعم الأصالة في كل رشفة.. حبوب قهوة فاخرة</p>
            <a href="#" class="btn btn-coffee btn-lg mt-3">تصفح المنتجات</a>
        </div>
    </header>

    <!-- قسم تجريبي للمنتجات -->
    <div class="container my-5">
        <h2 class="text-center mb-4" style="color: #6f4e37;">أحدث المنتجات</h2>
        <div class="row">
            <!-- منتج 1 -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5>قهوة كولومبي</h5>
                        <p class="text-muted">نكهة قوية ومميزة</p>
                        <button class="btn btn-outline-dark btn-sm">التفاصيل</button>
                    </div>
                </div>
            </div>
            <!-- منتج 2 -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5>قهوة إثيوبي</h5>
                        <p class="text-muted">حموضة فاكهية رائعة</p>
                        <button class="btn btn-outline-dark btn-sm">التفاصيل</button>
                    </div>
                </div>
            </div>
             <!-- منتج 3 -->
             <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5>إسبريسو بليند</h5>
                        <p class="text-muted">مزيج خاص للماكينات</p>
                        <button class="btn btn-outline-dark btn-sm">التفاصيل</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>