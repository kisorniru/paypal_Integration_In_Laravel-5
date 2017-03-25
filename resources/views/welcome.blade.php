<!DOCTYPE html>
<html>
<head>
    <title>laravel-with-paypal</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <br>
        <br>
        <br>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            <center>{{ session('error') }}</center>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success">
                            <center>{{ session('success') }}</center>
                        </div>
                    @endif
                    <div class="panel-heading">Paywith Paypal</div>
                    <div class="panel-body">
                        {!! Form::open(['route'=> array('store'), 'method'=>'POST','files'=>'true', 'class'=>'']) !!}
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('product') ? ' has-error' : '' }}">
                                <label for="product" class="col-md-4 control-label">Product</label>
                                <div class="col-md-6">
                                    <input id="product" type="text" class="form-control" name="product" value="{{ old('product') }}" autofocus>
                                    @if ($errors->has('product'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('product') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <br>
                            <br>
                            <br>
                            <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                                <label for="price" class="col-md-4 control-label">Amount</label>
                                <div class="col-md-6">
                                    <input id="price" type="text" class="form-control" name="price" value="{{ old('price') }}" autofocus>
                                    @if ($errors->has('price'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('price') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <center>
                                        <input type="submit" name="" class="btn btn-primary" value="Pay">
                                    </center>
                                </div>
                            </div>
                            <br>
                            <br>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <center>
                                        <p>you'll be taken to PayPal to complete your payment.</p>
                                    </center>
                                </div>
                            </div>
                            
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>