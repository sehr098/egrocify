import { Component } from '@angular/core';
import {MatTableDataSource, MatTableModule} from '@angular/material/table';
import {MatIconModule} from '@angular/material/icon';
import { ColumnDefinition, SharedTableComponent, TableAction, TableData } from '../../shared/shared-table/shared-table.component';

@Component({
  selector: 'app-subscriptions',
  standalone: true,
  imports: [MatTableModule,MatIconModule,SharedTableComponent],
  templateUrl: './subscriptions.component.html',
  styleUrl: './subscriptions.component.scss'
})
export class SubscriptionsComponent {
  companyData: TableData[] = [
    { id: 1, Package: 'Test Company', TotalAmount: 'test-company', Months: 1, StartDate: 1, EndDate: 1, Status: 1},
    { id: 2, Package: 'Another Company', TotalAmount: 'another-company', Months: 2, StartDate: 5, EndDate: 1, Status: 1},
    { id: 3, Package: 'Test Company', TotalAmount: 'test-company', Months: 1, StartDate: 1, EndDate: 1, Status: 1},
    { id: 4, Package: 'Another Company', TotalAmount: 'another-company', Months: 2, StartDate: 5, EndDate: 1, Status: 1},
    { id: 5, Package: 'Test Company', TotalAmount: 'test-company', Months: 1, StartDate: 1, EndDate: 1, Status: 1},
    { id: 6, Package: 'Another Company', TotalAmount: 'another-company', Months: 2, StartDate: 5, EndDate: 1, Status: 1},
    { id: 7, Package: 'Test Company', TotalAmount: 'test-company', Months: 1, StartDate: 1, EndDate: 1, Status: 1},
    { id: 8, Package: 'Another Company', TotalAmount: 'another-company', Months: 2, StartDate: 5, EndDate: 1, Status: 1},
    { id: 9, Package: 'Test Company', TotalAmount: 'test-company', Months: 1, StartDate: 1, EndDate: 1, Status: 1},
    { id: 10, Package: 'Another Company', TotalAmount: 'another-company', Months: 2, StartDate: 5, EndDate: 1, Status: 1},
    { id: 11, Package: 'Another Company', TotalAmount: 'another-company', Months: 2, StartDate: 5, EndDate: 1, Status: 1},
    // Add more data as needed
  ];

  columns: ColumnDefinition[] = [
    { key: 'Package', header: 'Package' },
    { key: 'TotalAmount', header: 'Total Amount', backgroundColor: 'orange', textColor: 'black', customdiv: true },
    { key: 'Months', header: 'Months', backgroundColor: 'purple', textColor: 'black', customdiv: true },
    { key: 'StartDate', header: 'Start Date', backgroundColor: 'lightblue', textColor: 'black', customdiv: true },
    { key: 'EndDate', header: 'EndDate' , backgroundColor: 'peach', textColor: 'black', customdiv: false },
    { key: 'Status', header: 'Status' , backgroundColor: 'peach', textColor: 'black', customdiv: false },
  ];

  actions: TableAction[] = [
    { label: 'Action 1', action: this.action1 },
    { label: 'Action 2', action: this.action2 },
    { label: 'Action 3', action: this.action3 },
    { label: 'Action 4', action: this.action4 },
  ];

  action1(element: TableData) {
    console.log('Action 1 clicked', element);
  }

  action2(element: TableData) {
    console.log('Action 2 clicked', element);
  }

  action3(element: TableData) {
    console.log('Action 3 clicked', element);
  }

  action4(element: TableData) {
    console.log('Action 4 clicked', element);
  }

  PaymentData: TableData[] = [
    { id: 1, TransactionID: 'Test Company', PaymentMethod: 'test-company', PaymentType: 1, PaymentAmount: 1, PaymentDateAndTime: 1, Status: 1},
    { id: 2, TransactionID: 'Another Company', PaymentMethod: 'another-company', PaymentType: 2, PaymentAmount: 5, PaymentDateAndTime: 1, Status: 1},
    { id: 3, TransactionID: 'Test Company', PaymentMethod: 'test-company', PaymentType: 1, PaymentAmount: 1, PaymentDateAndTime: 1, Status: 1},
    { id: 4, TransactionID: 'Another Company', PaymentMethod: 'another-company', PaymentType: 2, PaymentAmount: 5, PaymentDateAndTime: 1, Status: 1},
    { id: 5, TransactionID: 'Test Company', PaymentMethod: 'test-company', PaymentType: 1, PaymentAmount: 1, PaymentDateAndTime: 1, Status: 1},
    { id: 6, TransactionID: 'Another Company', PaymentMethod: 'another-company', PaymentType: 2, PaymentAmount: 5, PaymentDateAndTime: 1, Status: 1},
    { id: 7, TransactionID: 'Test Company', PaymentMethod: 'test-company', PaymentType: 1, PaymentAmount: 1, PaymentDateAndTime: 1, Status: 1},
    { id: 8, TransactionID: 'Another Company', PaymentMethod: 'another-company', PaymentType: 2, PaymentAmount: 5, PaymentDateAndTime: 1, Status: 1},
    { id: 9, TransactionID: 'Test Company', PaymentMethod: 'test-company', PaymentType: 1, PaymentAmount: 1, PaymentDateAndTime: 1, Status: 1},
    { id: 10, TransactionID: 'Another Company', PaymentMethod: 'another-company', PaymentType: 2, PaymentAmount: 5, PaymentDateAndTime: 1, Status: 1},
    { id: 11, TransactionID: 'Another Company', PaymentMethod: 'another-company', PaymentType: 2, PaymentAmount: 5, PaymentDateAndTime: 1, Status: 1},
    // Add more data as needed
  ];

  Paymentcolumns: ColumnDefinition[] = [
    { key: 'TransactionID', header: 'Transaction ID' },
    { key: 'PaymentMethod', header: 'Payment Method', backgroundColor: 'orange', textColor: 'black', customdiv: true },
    { key: 'PaymentType', header: 'Payment Type', backgroundColor: 'purple', textColor: 'black', customdiv: true },
    { key: 'PaymentAmount', header: 'Payment Amount', backgroundColor: 'lightblue', textColor: 'black', customdiv: true },
    { key: 'PaymentDateAndTime', header: 'Payment Date And Time' , backgroundColor: 'peach', textColor: 'black', customdiv: false },
    { key: 'Status', header: 'Status' , backgroundColor: 'peach', textColor: 'black', customdiv: false },
  ];

  Paymentactions: TableAction[] = [
    { label: 'Action 1', action: this.paymentaction1 },
    { label: 'Action 2', action: this.paymentaction2 },
    { label: 'Action 3', action: this.paymentaction3 },
    { label: 'Action 4', action: this.paymentaction4 },
  ];

  paymentaction1(element: TableData) {
    console.log('Action 1 clicked', element);
  }

  paymentaction2(element: TableData) {
    console.log('Action 2 clicked', element);
  }

  paymentaction3(element: TableData) {
    console.log('Action 3 clicked', element);
  }

  paymentaction4(element: TableData) {
    console.log('Action 4 clicked', element);
  }

}
