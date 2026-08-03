@extends('layouts.default')

@section('title', 'PF Statement')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="page-title">Provident Fund Statement</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-12 text-center mb-4">
            <h4>ACME Corporation</h4>
            <p>123 Business Road, Corporate City</p>
            <h5>Provident Fund Statement for 2026</h5>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <table class="table table-bordered table-sm">
                <tbody>
                    <tr>
                        <th class="bg-light" style="width: 30%">Employee Name:</th>
                        <td>John Doe</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Employee ID:</th>
                        <td>EMP-001</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Department:</th>
                        <td>Engineering</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-bordered table-sm">
                <tbody>
                    <tr>
                        <th class="bg-light" style="width: 30%">PF Account No:</th>
                        <td>PF-8XJ93KAL</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Date of Joining:</th>
                        <td>15 Jan, 2020</td>
                    </tr>
                    <tr>
                        <th class="bg-light">Status:</th>
                        <td><span class="badge bg-success">Active</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="table table-bordered table-striped text-center">
                <thead class="bg-light">
                    <tr>
                        <th>Month</th>
                        <th>Employee Contribution</th>
                        <th>Company Contribution</th>
                        <th>Withdrawals</th>
                        <th>Monthly Balance</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Mock Data to match screenshot 8 -->
                    <tr>
                        <td>January</td>
                        <td>$ 1,500.00</td>
                        <td>$ 1,500.00</td>
                        <td>$ 0.00</td>
                        <td>$ 3,000.00</td>
                    </tr>
                    <tr>
                        <td>February</td>
                        <td>$ 1,500.00</td>
                        <td>$ 1,500.00</td>
                        <td>$ 0.00</td>
                        <td>$ 6,000.00</td>
                    </tr>
                    <tr>
                        <td>March</td>
                        <td>$ 1,500.00</td>
                        <td>$ 1,500.00</td>
                        <td>$ 0.00</td>
                        <td>$ 9,000.00</td>
                    </tr>
                    <tr>
                        <td>April</td>
                        <td>$ 1,500.00</td>
                        <td>$ 1,500.00</td>
                        <td>$ 0.00</td>
                        <td>$ 12,000.00</td>
                    </tr>
                    <tr>
                        <td>May</td>
                        <td>$ 1,500.00</td>
                        <td>$ 1,500.00</td>
                        <td>$ 2,000.00</td>
                        <td>$ 13,000.00</td>
                    </tr>
                    <tr>
                        <td>June</td>
                        <td>$ 1,500.00</td>
                        <td>$ 1,500.00</td>
                        <td>$ 0.00</td>
                        <td>$ 16,000.00</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="fw-bold bg-light">
                        <td>Total</td>
                        <td>$ 9,000.00</td>
                        <td>$ 9,000.00</td>
                        <td>$ 2,000.00</td>
                        <td>$ 16,000.00</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6 offset-md-6">
            <table class="table table-bordered table-sm">
                <tbody>
                    <tr>
                        <th class="bg-light w-50">Opening Balance:</th>
                        <td class="text-end">$ 0.00</td>
                    </tr>
                    <tr>
                        <th class="bg-light w-50">Total Contributions:</th>
                        <td class="text-end text-success">+ $ 18,000.00</td>
                    </tr>
                    <tr>
                        <th class="bg-light w-50">Interest Earned (8%):</th>
                        <td class="text-end text-success">+ $ 640.00</td>
                    </tr>
                    <tr>
                        <th class="bg-light w-50">Total Withdrawals:</th>
                        <td class="text-end text-danger">- $ 2,000.00</td>
                    </tr>
                    <tr class="fw-bold">
                        <th class="bg-light w-50">Closing Balance:</th>
                        <td class="text-end">$ 16,640.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="row mt-3 text-center">
        <div class="col-12">
            <button class="btn btn-primary" onclick="window.print()">Print Statement</button>
            <button class="btn btn-danger">Download PDF</button>
        </div>
    </div>
</div>
@endsection
