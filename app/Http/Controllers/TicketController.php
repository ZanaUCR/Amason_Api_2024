<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    public function store(Request $request)
    {

        $request->validate([
            'order_package' => 'required|string',
            'claim_type' => 'required|string',
            'subject' => 'required|string',
            'description' => 'required|string',
            'file' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'notify_by' => 'nullable|string',
        ]);

        // Handle file upload if exists
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('tickets_files', 'public'); // Esto guarda en storage/app/public/tickets_files
        }

        // Create a new ticket
        $ticket = Ticket::create([
            'order_package' => $request->order_package,
            'claim_type' => $request->claim_type,
            'subject' => $request->subject,
            'description' => $request->description,
            'file' => $filePath, // Store the file path in the database
            'notify_by' => $request->notify_by,
            'user_id' => auth()->id()
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

}