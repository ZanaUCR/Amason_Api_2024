<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Message; 

class TicketController extends Controller
{
    public function store(Request $request)
    {


        if (!auth()->check()) {
            return response()->json(['error' => 'User is not authenticated'], 401);
        }
        if (!auth()->check()) {
            return response()->json(['error' => 'User is not authenticated'], 401);
        }
        
        $request->validate([
            'order_package' => 'string',
            'claim_type' => 'required|string',
            'subject' => 'required|string',
            'description' => 'required|string',
            'file' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'notify_by' => 'nullable|string',
        ]);


        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('tickets_files', 'public'); 
        }

        $userId = auth()->id();

        // Create a new ticket
        $ticket = Ticket::create([
            'order_package' => $request->order_package,
            'claim_type' => $request->claim_type,
            'status'=> 'open',
            'subject' => $request->subject,
            'description' => $request->description,
            'file' => $filePath, // Store the file path in the database
            'notify_by' => $request->notify_by,
            'user_id' => $userId
        ]);

        return response()->json($ticket, 201);
    }

    // List all tickets
    public function index()
    {
        $tickets = Ticket::all();
        return response()->json($tickets);
    }

    // Show a specific ticket
    public function show($id)
    {
         $ticket = Ticket::where('id', $id)->where('user_id', auth()->id())->first();

        if ($ticket) {
            // Generate a full URL for the file if it exists
            if ($ticket->file) {
                $ticket->file_url = asset('storage/' . $ticket->file);
            } else {
                $ticket->file_url = null; // If there's no file, return null
            }

            return response()->json($ticket);
        }

        return response()->json(['message' => 'Ticket not found'], 404);
    }

    public function addMessage(Request $request, $ticket_id)
{
    $request->validate([
        'message' => 'required|string',
    ]);

    $message = Message::create([
        'ticket_id' => $ticket_id,
        'user_id' => auth()->id(), // Asume que el usuario está autenticado
        'message' => $request->message,
    ]);

    return response()->json($message, 201);
}

public function assignTicket(Request $request, $id)
{
    $ticket = Ticket::findOrFail($id);
    $adminId = $request->user()->id; // Obtener el ID del usuario autenticado
    $ticket->admin_id = $adminId; // Asignar el ID del administrador que tomará el ticket
    $ticket->save();

    return response()->json($ticket);
}
public function unassignedTickets(Request $request)
{
    if ($request->user()->hasRole('admin')) {
    $tickets = Ticket::whereNull('admin_id')->get();
    return response()->json($tickets);
    }
    else {
        return response()->json(['error' => 'Unauthorized'], 401);
    }   
}

public function assignedTickets(Request $request)
{
    if ($request->user()->hasRole('admin')) {
        $admin_id = $request->user()->id; 
        $tickets = Ticket::where('admin_id', $admin_id)->get();
        return response()->json($tickets);
    } else {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
public function userTickets(Request $request)
{
    // Obtén el ID del usuario logueado
    $userId = $request->user()->id;

    // Recupera todos los tickets que pertenecen al usuario logueado
    $tickets = Ticket::where('user_id', $userId)->get();

    return response()->json($tickets);
}

public function getMessages($ticket_id)
    {
        // Verifica que el ticket exista
        $ticket = Ticket::find($ticket_id);
        
        if (!$ticket) {
            return response()->json(['message' => 'Ticket no encontrado'], 404);
        }

        // Obtener los mensajes asociados al ticket
        $messages = Message::where('ticket_id', $ticket_id)->get();

        return response()->json($messages, 200);
    }

    public function closeTicket($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->status = 'closed';
        $ticket->save();

        return response()->json($ticket);
    }

}