<?php

namespace App\Http\Controllers;

use App\Attachment;

class AttachmentController extends Controller
{
    /**
     * @param Attachment $attachment
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Attachment $attachment)
    {
        $attachment->delete();
        return back();
    }
}
