<?php
if (isset($_GET['search'])) {
    $apiKey = "3cfbd083";
    $search = urlencode($_GET['search']);
    $url = "http://www.omdbapi.com/?apikey=$apiKey&s=$search";
    
    $response = file_get_contents($url);
    $movies = json_decode($response, true);
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rekomendasi Film</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            height: 100%;
        }
        .card-img-top {
            object-fit: cover;
            height: 400px; 
        }
        .rating {
            font-size: 1.2em;
            color: rgb(211, 132, 4);
            font-weight: bold;
        }
        .rating-container {
            display: flex;
            align-items: center;
            gap: 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Movie Rate</a>
        </div>
    </nav>

    <div class="container">
        <div class="row mt-3">
            <div class="col">
                <h1 class="text-center">Check Your Favorite Movies</h1>
                <form method="GET" class="input-group mb-3">
                    <input type="text" class="form-control" name="search" placeholder="Movie Title" required>
                    <button class="btn btn-primary" type="submit">Search</button>
                </form>
            </div>
        </div>

        <?php if (isset($movies) && $movies['Response'] == "True"): ?>
            <div class="row mt-4">
                <?php foreach ($movies['Search'] as $movie): ?>
                    <?php
                        $movieID = $movie['imdbID'];
                        $detailUrl = "http://www.omdbapi.com/?apikey=$apiKey&i=$movieID";
                        $detailResponse = file_get_contents($detailUrl);
                        $movieDetail = json_decode($detailResponse, true);
                        $rating = isset($movieDetail['imdbRating']) ? $movieDetail['imdbRating'] : 'N/A';
                    ?>
                    <div class="col-md-3 mb-4">
                        <div class="card d-flex flex-column h-100">
                            <img src="<?php echo $movie['Poster'] !== "N/A" ? $movie['Poster'] : 'https://via.placeholder.com/300x400?text=No+Image'; ?>" 
                                 class="card-img-top" alt="Poster">
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h5 class="card-title"><?php echo $movie['Title']; ?></h5>
                                    <p class="card-text">(<?php echo $movie['Year']; ?>)</p>
                                </div>
                                <div class="rating-container">
                                    <strong>IMDb Rating:</strong>
                                    <span class="rating">★ <?php echo $rating; ?> / 10</span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif (isset($movies)): ?>
            <div class="alert alert-danger text-center" role="alert">
                Film tidak ditemukan.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
