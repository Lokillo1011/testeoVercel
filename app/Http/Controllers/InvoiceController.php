<?php

namespace App\Http\Controllers;

use App\Mail\EnviarInvoicePDF;
use App\Mail\VerificarUsuario;
use App\Models\invoiceDetalle;
use App\Models\invoices;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    /**
     * Metodo para crear una invoice
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createInvoice(Request $request)
    {
        //aqui se crea la invoice
        DB::beginTransaction();
        try {

            //recibe un archivo y lo guarda en el public storage de laravel
            $file = $request->file('imagen');
            $nombre = $file->getClientOriginalName();
            $nombre = $file->getClientOriginalName();
            //quita espacios en blanco y caracteres especiales
            $nombre = preg_replace('([^A-Za-z0-9.])', '', $nombre);
            $nombre = Carbon::now()->timestamp . '_' . $nombre;
            $invoice = invoices::create([
                'nombre_invoice'             => $request->nombre_invoice,
                'nombre_compania'            => $request->nombre_compania,
                'url_sitio_web'              => $request->url_sitio_web,
                'numero_compania'            => $request->numero_compania,
                'correo_electronico'         => $request->correo_electronico,
                'url_logo'                   => '',
                'direccion'                  => $request->direccion,
                'nombre_cliente'             => $request->nombre_cliente,
                'correo_electronico_cliente' => $request->correo_cliente,
                'numero_factura'             => $request->numero_factura,
                'fecha_factura'              => $request->fecha_factura,
                'fecha_vencimiento_factura'  => $request->fecha_vencimiento,
                'comentario_factura'         => isset($request->comentario_factura) ? $request->comentario_factura : '',
                'subtotal_factura'           => $request->subtotal_factura,
                'impuesto_factura'           => $request->impuesto_factura,
                'total_factura'              => $request->total_factura,
                'id_usuario'                 => $request->id_usuario,
            ]);

            $path = $file->storeAs('public/' . $invoice->id, $nombre);
            $invoice->update([
                'url_logo' => $path,
            ]);
            if (isset($request->detalle)) {
                if (is_array($request->detalle)) {
                    $request->detalle = json_encode($request->detalle);
                }
                if (is_string($request->detalle)) {
                    $request->detalle = json_decode($request->detalle);
                }
                foreach ($request->detalle as $detalle) {
                    if (is_object($detalle)) {
                        $detalle = json_decode(json_encode($detalle), true);
                    }
                    invoiceDetalle::create([
                        'id_invoice'      => $invoice->id,
                        'nombre_producto' => $detalle['nombre_producto'] ?? $detalle->nombre_producto,
                        'cantidad'        => $detalle['cantidad'] ?? $detalle->cantidad,
                        'precio'          => $detalle['precio'] ?? $detalle->precio,
                    ]);
                }
            }
            DB::commit();
            //usa el servicio de crear pdf para crear el pdf de la invoice y enviarlo por correo
            $pdf = self::generatePdfB64($invoice->id);
            //pdf trae un base64

            $user = User::where('id', $request->id_usuario)->first();
            $user->pdf = $pdf;
            $user->invoicename = $invoice->nombre_invoice;
            Mail::to($user->email)->send(new EnviarInvoicePDF($user));
            unlink(storage_path('app/' . $user->invoicename . '.pdf'));
            return response()->json([
                'message' => 'Invoice creada correctamente',
                'data'    => $invoice->with('invoiceDetalle')->first(),
                'status'  => 'success',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage(),
                                     'status'  => 'error']);
        }
    }

    /**
     * Metodo para obtener todas las invoices de un usuario
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInvoices(Request $request)
    {
        $invoices = invoices::where('id_usuario', $request->id_usuario)->with('invoiceDetalle')
            ->when(isset($request->activo), function ($query) use ($request) {
                return $query->where('activo', $request->activo);
            })
            ->get();
        return response()->json([
            'message' => count($invoices) > 0 ? 'Invoices obtenidas correctamente' : 'No se encontraron invoices',
            'data'    => $invoices,
            'status'  => 'success',
        ], 200);
    }

    /**
     * Metodo para obtener una invoice por id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInvoiceById($id)
    {
        $invoice = invoices::where('id', $id)->with('invoiceDetalle')->first();
        if (!$invoice) return response()->json(['message' => 'No se encontro la invoice', 'status' => 'error'], 404);
        return response()->json([
            'message' => 'Invoice obtenida correctamente',
            'data'    => $invoice,
            'status'  => 'success',
        ], 200);
    }

    /**
     * Metodo para actualizar una invoice
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateInvoice(Request $request)
    {
        DB::beginTransaction();
        try {
            $invoice = invoices::where('id', $request->id)->first();
            if (!$invoice) return response()->json(['message' => 'No se encontro la invoice', 'status' => 'error'], 404);
            //si trae imagen se actualiza y se elimina la anterior
            if ($request->file('imagen')) {
                //recibe un archivo y lo guarda en el public storage de laravel
                $file = $request->file('imagen');
                $nombre = $file->getClientOriginalName();
                //quita espacios en blanco y caracteres especiales
                $nombre = preg_replace('([^A-Za-z0-9.])', '', $nombre);
                $nombre = Carbon::now()->timestamp . '_' . $nombre;
                $path = $file->storeAs('public/' . $invoice->id, $nombre);
                unlink(storage_path('app/' . $invoice->url_logo));
                $invoice->update([
                    'url_logo' => $path,
                ]);
            }
            $invoice->update([
                'nombre_invoice'     => $request->nombre_invoice,
                'nombre_compania'    => $request->nombre_compania,
                'url_sitio_web'      => $request->url_sitio_web,
                'numero_compania'    => $request->numero_compania,
                'correo_electronico' => $request->correo_electronico,
                'direccion'          => $request->direccion,
                'nombre_cliente'     => $request->nombre_cliente,
                'correo_cliente'     => $request->correo_cliente,
                'numero_factura'     => $request->numero_factura,
                'fecha_factura'      => $request->fecha_factura,
                'fecha_vencimiento'  => $request->fecha_vencimiento,
                'comentario_factura' => isset($request->comentario_factura) ? $request->comentario_factura : '',
                'subtotal_factura'   => $request->subtotal_factura,
                'impuesto_factura'   => $request->impuesto_factura,
                'total_factura'      => $request->total_factura,
                'id_usuario'         => $request->id_usuario,
            ]);

            $invoiceDetalle = invoiceDetalle::where('id_invoice', $request->id)->get();
            if (count($invoiceDetalle) > 0 && isset($request->detalle)) {
                if (isset($request->detalle)) {
                    if (is_array($request->detalle)) {
                        $request->detalle = json_encode($request->detalle);
                    }
                    if (is_string($request->detalle)) {
                        $request->detalle = json_decode($request->detalle);
                    }
                    foreach ($request->detalle as $detalle) {
                        if (is_object($detalle)) {
                            $detalle = json_decode(json_encode($detalle), true);
                        }
                        //si el detalle ya existe se actualiza si no se crea
                        if (isset($detalle['id']) && $detalle['id'] != null && $detalle['id'] > 0) {
                            $detalleInvoice = invoiceDetalle::where('id', $detalle['id'])->first();
                            if ($detalleInvoice) {
                                $detalleInvoice->update([
                                    'nombre_producto' => $detalle['nombre_producto'] ?? $detalle->nombre_producto,
                                    'cantidad'        => $detalle['cantidad'] ?? $detalle->cantidad,
                                    'precio'          => $detalle['precio'] ?? $detalle->precio,
                                ]);
                            }
                        } else {
                            invoiceDetalle::create([
                                'id_invoice'      => $invoice->id,
                                'nombre_producto' => $detalle['nombre_producto'] ?? $detalle->nombre_producto,
                                'cantidad'        => $detalle['cantidad'] ?? $detalle->cantidad,
                                'precio'          => $detalle['precio'] ?? $detalle->precio,
                            ]);
                        }
                    }
                }
            }
            DB::commit();
            //usa el servicio de crear pdf para crear el pdf de la invoice y enviarlo por correo
            $pdf = self::generatePdfB64($invoice->id);
            //pdf trae un base64

            $user = User::where('id', $request->id_usuario)->first();
            $user->pdf = $pdf;
            $user->invoicename = $invoice->nombre_invoice;
            Mail::to($user->email)->send(new EnviarInvoicePDF($user));
            unlink(storage_path('app/' . $user->invoicename . '.pdf'));
            return response()->json([
                'message' => 'Invoice actualizada correctamente',
                'data'    => $invoice,
                'status'  => 'success',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage(),
                                     'status'  => 'error']);
        }
    }

    /**
     * Metodo para eliminar una invoice de forma logica
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteInvoice($id)
    {
        DB::beginTransaction();
        try {
            $invoice = invoices::where('id', $id)->first();
            if (!$invoice) return response()->json(['message' => 'No se encontro la invoice', 'status' => 'error'], 404);
            $invoice->update([
                'activo' => 0,
            ]);
            DB::commit();
            return response()->json([
                'message' => 'Invoice eliminada correctamente',
                'data'    => $invoice,
                'status'  => 'success',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage(),
                                     'status'  => 'error']);
        }
    }

    /**
     * Metodo para eliminar una invoice de forma fisica
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteInvoicePermanently($id)
    {
        DB::beginTransaction();
        try {
            $invoice = invoices::where('id', $id)->first();
            if (!$invoice) return response()->json(['message' => 'No se encontro la invoice', 'status' => 'error'], 404);

            //borrar los detalles de la invoice
            $detalles = invoiceDetalle::where('id_invoice', $id)->get();
            if (count($detalles) > 0) {
                foreach ($detalles as $detalle) {
                    $detalle->delete();
                }
            }
            //se eliminan todos los archivos de la invoice
            $url_anterior = str_replace(asset('storage/'), '', $invoice->url_logo);
            $invoice->delete();
            DB::commit();
            return response()->json([
                'message' => 'Invoice eliminada correctamente',
                'data'    => $invoice,
                'status'  => 'success',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage(),
                                     'status'  => 'error']);
        }
    }

    public function editInvoice($id)
    {
        $invoice = invoices::where('id', $id)->with('invoiceDetalle')->first();
        if (!$invoice) return redirect()->route('inicio')->with('errors', 'No se encontro la invoice');
        $invoice->url_logo = base64_encode(file_get_contents(storage_path('app/' . $invoice->url_logo)));
        return view('invoices.editInvoice', compact('invoice'));
    }

    public function generatePdf($id)
    {
        $invoice = invoices::where('id', $id)->with('invoiceDetalle')->first();
        if (!$invoice) return redirect()->route('inicio')->with('errors', 'No se encontro la invoice');
        $invoice->url_logo = base64_encode(file_get_contents(storage_path('app/' . $invoice->url_logo)));
        $pdf = PDF::loadView('pdf.invoice', compact('invoice'));
        return $pdf->download($invoice->nombre_invoice . '.pdf');
    }

    public function generatePdfB64($id)
    {
        $invoice = invoices::where('id', $id)->with('invoiceDetalle')->first();
        if (!$invoice) return redirect()->route('inicio')->with('errors', 'No se encontro la invoice');
        $invoice->url_logo = base64_encode(file_get_contents(storage_path('app/' . $invoice->url_logo)));
        $pdf = PDF::loadView('pdf.invoice', compact('invoice'));
        return base64_encode($pdf->download($invoice->nombre_invoice . '.pdf'));
    }

    public function reactivateInvoice($id)
    {
        DB::beginTransaction();
        try {
            $invoice = invoices::where('id', $id)->first();
            if (!$invoice) return response()->json(['message' => 'No se encontro la invoice', 'status' => 'error'], 404);
            $invoice->update([
                'activo' => 1,
            ]);
            DB::commit();
            return response()->json([
                'message' => 'Invoice reactivada correctamente',
                'data'    => $invoice,
                'status'  => 'success',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage(),
                                     'status'  => 'error']);
        }
    }
}
