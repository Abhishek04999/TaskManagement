<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .details-label {
            font-weight: bold;
        }

        .details-value {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Task Details</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 details-label">Title:</div>
                            <div class="col-md-8 details-value">{{ $task->title }}</div>
                            <div class="col-md-4 details-label">Category:</div>
                            <div class="col-md-8 details-value">{{ $task->category }}</div>
                            <div class="col-md-4 details-label">Description:</div>
                            <div class="col-md-8 details-value">{{ $task->description }}</div>
                            <div class="col-md-4 details-label">Deadline:</div>
                            <div class="col-md-8 details-value">{{ $task->deadline }}</div>
                            <div class="col-md-4 details-label">Status:</div>
                            <div class="col-md-8 details-value">{{ $task->status }}</div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="javascript:history.go(-1);" class="btn btn-secondary">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
