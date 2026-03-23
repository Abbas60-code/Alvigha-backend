<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Database\QueryException;

class ContactController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Contact API is running.']);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name'    => 'required|string|max:255',
                'email'   => 'required|email|max:255',
                'phone'   => 'nullable|string|max:20',
                'message' => 'required|string',
            ]);

            Contact::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Your message has been sent successfully! 🎉',
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (QueryException $e) {
            \Log::error('Contact form DB error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Database is temporarily unavailable. Please try again in a moment.',
            ], 503);
        } catch (\Exception $e) {
            \Log::error('Contact form error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error while submitting the form.',
            ], 500);
        }
    }
}