<?php

namespace App\Http\Controllers;

use App\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listNotifications(Request $request){

        if ($request->has('search')&&!empty($request->search)) {
           $notifications = Notification::withTrashed()->where('detail','LIKE','%'.$request->search.'%')
                                        ->orWhere('status','LIKE','%'.$request->search.'%')     
                                        ->paginate(10);
        }else{
            $notifications = Notification::withTrashed()->get();
        }

        return view('notifications.mainNotifications',['data'=>$notifications]);
    }

    public function createNotification(){
        return view('notifications.newNotification');
    }

    public function saveNotification(Request $request){

        $notification = new Notification();

        $notification->detail = $request->detail;
        $notification->status = $request->status;
        $notification->save();

        if ($notification->status=='pendiente') {
            $notification->delete();
        }else{
            $notification->deleted_at = NULL;
            $notification->update();
        }

        return redirect()->route('notification.list');

    }

    public function desactivateNotification(Request $request){

        $notification = Notification::findOrFail($request->notification_id);
        $notification->delete();
        return back()->with(['message'=>'Notificación Desactivada Correctamente']);
    }

    public function activateNotification(Request $request){

        Notification::onlyTrashed()->find($request->notification_id)->restore();
        return back()->with(['message'=>'Notificación Activada Correctamente']);

    }

    public function destroyPublication(Request $request){
        $notification = Notification::withTrashed()->find($request->notification_id)->forceDelete();
        return back()->with(['message'=>'Notificación Eliminada Correctamente']);
    }

}
