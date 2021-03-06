@extends('admin.index')
@section('admin.main')
    <div class="row">
        <div class="col-lg-12">
            <h4 class="page-header">
                <i class="fa fa-fw fa-user-secret"></i> Anonymous Files 
            </h4>
            <ol class="breadcrumb">
                <li class="active">
                    <i class="fa fa-dashboard"></i> Dashboard
                    
                </li>                
                   
                <li class="active">
                    <i class="fa fa-fw fa-files-o"></i> Files Settings 
                </li>
             
                <li >
                <i class="fa fa-fw fa-user-secret"></i> Anonymous Files 
                </li>
            </ol>
        </div>
    </div>
    
    <div class="row">

            @if($errors->any() )
                <div class="col-md-12">
                    <div style="padding:8px;margin-bottom:25px;"
                     class="alert alert-danger text-left" role="alert">
                    <ul style="list-style:none;" >
                        {{ implode('',$errors->all('
                            <li ><i class="fa fa-exclamation-circle"></i> :message</li>
                            '))
                         }}
                    </ul>
                    </div>
                </div>

            @endif    
          
           @if(Session::has('Message'))
                  <div class="col-md-12">
                  {{ Session::get('Message') }}
                </div>

            @endif
        <div class="col-md-6 ">

             <p class="align-right">
              <a href="{{ url('admin/guestFiles/deleteExpired') }}"
                  class="confirm btn btn-info btn-md" >
                  <i class="fa fa-trash"></i> Delete Anonymous Expired Files
              </a>
            </p>

        </div>

        <div class="col-md-6 ">
            <p class="align-right">
              <a href="{{ url('admin/guestFiles/deleteAll') }}"
                  class="confirm btn btn-danger btn-md">
                   <i class="fa fa-trash"></i> Delete All Anonymous Files
              </a>
            </p>
        </div>               
        
        <div class="col-md-12">
           
            <div class="panel panel-default">
                  <div class="panel-heading">
                    
                         Uploaded Files  
                    
                    </div>
                  
                <div class="panel-body">
                
            <table id="table-pagination" data-toggle="table"

               data-classes="table table-bordered table-striped table-hover"
               data-search="true"
               data-search-align="left"

            >
              <thead>
                <tr >
                    <th data-sortable="true" data-field="id">
                    <i class="fa fa-list-ol"></i>
                    </th>
                    <th data-field="type"><i class="fa fa-filter"></i></th>
                    <th data-search="true" data-field="name"><i class="fa fa-file-text-o"></i> Name</th>
                    <th data-search="false" data-field="size">
                        <i class="fa fa-crosshairs"></i> Size
                    </th>
                    <th data-search="false" data-field="uploadAt"><i class="fa fa-clock-o"></i> Upload time</th>
                    <th data-search="false" data-field="ExpireAfter"><i class="fa fa-clock-o"></i> Expire After</th>

                    <th data-search="false" data-field="fileUploader">
                      <i class="fa fa-fw fa-user-secret"></i>  IP 
                    </th>
                    <th data-search="false" data-field="fileLink"><i class="fa fa-link"></i> File Link</th>
                    <th data-search="false" data-field="fileOptions"> Options</th>

                </tr>                
             
                </thead>
                <tbody style="text-align:left;">
                   
                    @foreach($data['files'] as $key=>$file)
                    
                    <tr id="tr-{{ $key+1 }}" >
                        <td style="display:none;" data-field="id">

{{ ((($data['files']->getCurrentPage() - 1)* $data['files']->getPerPage()) + $key+1) }}
                       
                        </td>
                        <td data-field="type">
                          @if ($file->fileExt === 'jpg' )
                            <i class="fa fa-picture-o"></i> {{ $file->fileExt }} 
                          @elseif ($file->fileExt === 'jpg')
                            <i class="fa fa-picture-o"></i> {{ $file->fileExt }}
                          @elseif ($file->fileExt === 'png')
                            <i class="fa fa-picture-o"></i> {{ $file->fileExt }}
                          @elseif ($file->fileExt === 'zip')
                            <i class="fa fa-file-archive-o"></i> {{ $file->fileExt }}
                          @elseif ($file->fileExt === 'mp3')
                            <i class="fa fa-music"></i> {{ $file->fileExt }}
                          @elseif ($file->fileExt === 'exe')
                            <i class="fa fa-cog"></i> {{ $file->fileExt }}
                          @elseif ($file->fileExt === 'mp4')
                            <i class="fa fa-film"></i> {{ $file->fileExt }}
                          @else
                            <i class="fa fa-file-text-o"></i> {{ $file->fileExt }}
                          @endif
                        </td>
                        <td data-field="name">
                          {{ mb_substr($file->fileName,0,10,"utf-8") }}
                        </td>
                        <td data-field="size">
                          {{ formatFileSize( $file->fileSize ) }}
                        </td>
                        <td data-field="uploadAt">
                          {{date("F j, Y, g:i ",strtotime($file->created_at));  }}
                        </td>
                
                        <td data-field="ExpireAfter">
                           <?php
                                $created = new Carbon($file->created_at);
                                $now     = Carbon::now(); 
                                
                                $created = $now->diffInDays($created);
                                $expireAfter =  uploadSettings::find(1)->fileExpireLimit - $created;
                                
                                if($expireAfter <= 0 ) {   
                                    echo "<b style='color:#D62C1A;'> Expired </b>"; 
                                }else{                            
                                    echo "$expireAfter Days";
                                }
                            ?>
                        </td>

                        <td data-field="fileUploader">
                             {{ $file->userIp }}
                        </td>
                        <td data-field="fileLink">
                            <a target="_blank" style="text-decoration:underline;
                            font-size:14px;" href="{{ $file->filePath }}">
                             <i class="fa fa-external-link"></i>
                              {{ mb_substr(html_entity_decode($file->fileName),0,10,"utf-8") }}
                            </a>
                        </td>
                        <td data-field="fileOptions">
                            <a style="font-size:18px;" 
                             href="{{ url('admin/files/delete/'.$file->id) }}"
                                
                             >
                              <i class="fa fa-trash"></i>
                            </a>
                            
  
                        </td>
                    </tr>
                    
                    @endforeach
                </tbody>
              
            </table>
                {{ $data['files']->links()  }} 
               
                </div>
                
            </div>

        </div>


        



    </div>


@endsection
@stop