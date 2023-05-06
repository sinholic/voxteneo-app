<div>
    {{ Form::token() }}
    @foreach($contents as $content)
        <?php 
            $type   = $content['type'];
            $state  = $content['state'] ?? '';
            $label  = isset($content['label']) ? $content['label'] : ucwords(str_replace("_", " ", $content['field']));
        ?>
        @if($type != 'hidden') 
            <div class="form-group">
                <label for="{{ $content['field'] }}">
                    {{ $label }}
                </label>
                @switch($type)
                    @case('filter_month_year')
                        {{ Form::select($content['field'], $years, $content['value'] ?? NULL, ['class' => 'form-control js-example-basic-single', $state]) }}
                        @break
                        
                    @case('select2')
                        {{ Form::select($content['field'], $content['data'], $content['value'] ?? NULL, ['placeholder'=> $label, 'class' => 'form-control js-example-basic-single', $state]) }}
                        @break

                    @case('select2_multiple')
                        {{ Form::select($content['field'], $content['data'], $content['value'] ?? NULL, ['placeholder'=> $label, 'class' => 'form-control js-example-basic-single', $state, 'multiple']) }}
                        @break

                    @case('password')
                        {{ Form::$type($content['field'], ['placeholder'=> $label, 'class' => 'form-control', $state]) }}
                        @break

                    @case('textarea')
                    @case('text')
                    @case('number')
                        @if (isset($content['key']))
                            {{ Form::$type($content['field'], $content['value'] ?? NULL, ['placeholder'=> $label, 'class' => 'form-control', $state]) }}
                        @else
                            {{ Form::$type($content['field'], $content['value'] ?? NULL, ['placeholder'=> $label, 'class' => 'form-control', $state]) }}
                        @endif
                        @if(isset($content['has_logs']) && $content['has_logs'])
                        <ul>
                            <?php
                                $logs->where('field', $content['field'])->each(function($log){
                                    echo '<li>'.nl2br(stripcslashes($log->value)).'</li>';
                                })
                            ?>
                        </ul>
                        @endif
                        @break

                    @case('wsywig')
                        {{ Form::textarea($content['field'], $content['value'] ?? NULL, ['placeholder'=> $label, 'class' => 'form-control', 'rows' => '5', 'style' => 'height:auto', $state]) }}
                        @break

                    @case('currency')
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup{{ $content['field'] }}">IDR</span>
                            </div>
                            {{ Form::text($content['field'], $content['value'] ?? NULL, [
                                'placeholder'=> $label, 'class' => 'form-control masking', 
                                'id' => 'inputGroup'.$content['field'], 
                                'aria-describedby' => 'inputGroup'.$content['field'],
                                'data-input-mask' => 'money', 
                                $state
                            ]) }}
                        </div>
                        @break

                    @case('file')
                        <div class="row">
                            <div class="col-10">
                                {{ Form::file($content['field'], ['placeholder'=> $label, 'class' => 'form-control-file', $state]) }}
                            </div>
                            <div class="col-2">
                                @if(isset($content['value']))
                                <a href="{{ asset('storage/uploads/cv/'.$content['value']) }}" target="_blank">
                                    <i class="fa fa-eye" aria-hidden="true"></i> View CV
                                </a>
                                @endif
                            </div>
                        </div>
                        @break

                    @case('selectMonth')
                        {{ Form::selectMonth($content['field'], $content['value'] ?? NULL, ['placeholder'=> $label, 'class' => 'form-control date-range-picker', $state]) }}
                        @break

                    @case('date')
                        {{ Form::text($content['field'], $content['value'] ?? NULL, ['placeholder'=> $label, 'class' => 'form-control single-date-picker', $state]) }}
                        @break

                    @case('daterange')
                        {{ Form::text($content['field'], $content['value'] ?? NULL, ['placeholder'=> $label, 'class' => 'form-control date-range-picker', $state]) }}
                        @break

                    @case('datetime')
                        {{ Form::text($content['field'], $content['value'] ?? NULL, ['placeholder'=> $label, 'class' => 'form-control single-datetime-picker', $state]) }}
                        @break
                    
                    @default
                @endswitch
                @error($content['field'])<p class="form-text text-danger">{{ $message }}</p>@enderror
            </div>
        @else
            {{ Form::hidden($content['field'], $content['value'] ?? NULL, ['class' => 'form-control']) }}
        @endif
    @endforeach
    @if ($edit)
        {{ link_to(url()->previous(), 'Cancel', ['class' => 'btn btn-warning']) }}
        {{ Form::submit('Submit', ['class' => 'btn btn-success', 'id' => 'submit']) }}
    @endif
</div>