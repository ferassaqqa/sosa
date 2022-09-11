
                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if (isset($errors) && $errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            @if ($failures)

                <table class="table table-danger">
                    <tr>
                        <th>رقم الصف</th>
                        {{-- <th>Attribute</th> --}}
                        <th>الخطأ</th>
                        <th>رقم الهوية</th>
                    </tr>

                    @foreach ($failures as $validation)
                        <tr>
                            <td>{{ $validation->row() }}</td>
                            {{-- <td>{{ $validation->attribute() }}</td> --}}
                            <td>
                                <ul style="list-style-type:none;">
                                    @foreach ($validation->errors() as $e)
                                        <li>{{ $e }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                {{ $validation->values()[$validation->attribute()] }}
                            </td>
                        </tr>
                    @endforeach
                </table>

            @endif


