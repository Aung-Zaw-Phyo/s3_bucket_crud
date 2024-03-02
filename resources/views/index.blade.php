<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <style>
        .container{
            width: 100%;
            height: 100vh;
            
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="col-lg-6 mx-auto">

            @if ($message = Session::get('info'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                            <strong>{{ $message }}</strong>
                    </div>
            @endif
            @if ($message = Session::get('success'))
                    <div class="p-3">
                        <img src="{{ 'https://my-file-upload-test.s3.ap-northeast-1.amazonaws.com/images/'.$message }}" width="150" height="150" alt="">
                    </div>
            @endif

            <h3 class="text-center">
                File Upload To S3
            </h3>
            <form class="mt-5" action="upload" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="image" class="form-label">Image</label>
                <input type="file" name="image" id="image" class="form-control">
                <button type="submit" class="btn btn-primary mt-3">Upload</button>
            </form>
        </div>
        <div class="container py-5">
            <h3 class="text-center">All Images</h3>
            <div class="row g-2 mt-3">
                @foreach ($images as $image)
                    <div class="col-lg-3 p-3">
                        <img src="{{ 'https://my-file-upload-test.s3.ap-northeast-1.amazonaws.com/images/'.$image->images }}" class="w-100 mt-2" alt="">
                        <div class="d-flex flex-column mt-3">
                            <form action="delete/{{ $image->id }}" method="POST">
                                @csrf
                                <input type="hidden" name="path" value="{{ $image->images }}">
                                <input type="hidden" name="product_id" value="{{ $image->product_id }}">
                                <button class="btn btn-primary">Delete</button>
                            </form>
                            <form action="update/{{ $image->id }}" class="mt-3" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="oldImg" value="{{ $image->images }}">
                                <input type="hidden" name="product_id" value="{{ $image->product_id }}">
                                <div class="input-group">
                                    <input type="file" name="newImg" class="form-control">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>