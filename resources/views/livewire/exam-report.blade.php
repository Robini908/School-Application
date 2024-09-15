<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title"><i class="icon-wrench mr-2"></i> Exam Report </h5>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form wire:submit.prevent="submit">
            <div class="row">
                <div class="col-md-10">
                    <fieldset>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exam_id" class="col-form-label font-weight-bold">Exam:</label>
                                    <select wire:model="exam_id" id="exam_id" name="exam_id" data-placeholder="Select Exam" class="form-control select" required>
                                        <option value="">Select Exam</option>
                                        @foreach($exams as $ex)
                                            <option value="{{ $ex->id }}">{{ $ex->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('exam_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="my_class_id" class="col-form-label font-weight-bold">Class:</label>
                                    <select wire:model="my_class_id" id="my_class_id" name="my_class_id" class="form-control select" required>
                                        <option value="">Select Class</option>
                                        @foreach($my_classes as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('my_class_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="section_id" class="col-form-label font-weight-bold">Section:</label>
                                    <select wire:model="section_id" id="section_id" name="section_id" data-placeholder="Select Class First" class="form-control select" required>
                                        <option value="">Select Section</option>
                                        @if($selected)
                                            @foreach($sections as $s)
                                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('section_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>

                <div class="col-md-2 mt-4">
                    <div class="text-right mt-1">
                        <button type="submit" class="btn btn-danger">Fix Errors <i class="icon-wrench2 ml-2"></i></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
