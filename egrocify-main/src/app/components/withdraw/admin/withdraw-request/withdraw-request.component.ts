import { Component } from '@angular/core';
import {MatTableDataSource, MatTableModule} from '@angular/material/table';
import {MatIconModule} from '@angular/material/icon';
import { ColumnDefinition, SharedTableComponent, TableAction, TableData } from '../../../../shared/shared-table/shared-table.component';

@Component({
  selector: 'app-withdraw-request',
  standalone: true,
  imports: [MatTableModule,MatIconModule,SharedTableComponent],
  templateUrl: './withdraw-request.component.html',
  styleUrl: './withdraw-request.component.scss'
})
export class WithdrawRequestComponent {
  companyData: TableData[] = [
    { id: 1, Username: 'Test Company', Amount: 'test-company', AccountType: 1, AccountNo: 1, AccountName: 1, Status: 1, CreatedAt : 1},
    { id: 2, Username: 'Another Company', Amount: 'another-company', AccountType: 2, AccountNo: 5, AccountName: 1, Status: 1, CreatedAt : 1},
    { id: 3, Username: 'Test Company', Amount: 'test-company', AccountType: 1, AccountNo: 1, AccountName: 1, Status: 1, CreatedAt : 1},
    { id: 4, Username: 'Another Company', Amount: 'another-company', AccountType: 2, AccountNo: 5, AccountName: 1, Status: 1, CreatedAt : 1},
    { id: 5, Username: 'Test Company', Amount: 'test-company', AccountType: 1, AccountNo: 1, AccountName: 1, Status: 1, CreatedAt : 1},
    { id: 6, Username: 'Another Company', Amount: 'another-company', AccountType: 2, AccountNo: 5, AccountName: 1, Status: 1, CreatedAt : 1},
    { id: 7, Username: 'Test Company', Amount: 'test-company', AccountType: 1, AccountNo: 1, AccountName: 1, Status: 1, CreatedAt : 1},
    { id: 8, Username: 'Another Company', Amount: 'another-company', AccountType: 2, AccountNo: 5, AccountName: 1, Status: 1, CreatedAt : 1},
    { id: 9, Username: 'Test Company', Amount: 'test-company', AccountType: 1, AccountNo: 1, AccountName: 1, Status: 1, CreatedAt : 1},
    { id: 10, Username: 'Another Company', Amount: 'another-company', AccountType: 2, AccountNo: 5, AccountName: 1, Status: 1, CreatedAt : 1},
    { id: 11, Username: 'Another Company', Amount: 'another-company', AccountType: 2, AccountNo: 5, AccountName: 1, Status: 1, CreatedAt : 1},
    // Add more data as needed
  ];

  columns: ColumnDefinition[] = [
    { key: 'Username', header: 'User Name', backgroundColor: 'orange', textColor: 'black', customdiv: true },
    { key: 'Amount', header: 'Amount' },
    { key: 'AccountType', header: 'Account Type', backgroundColor: 'purple', textColor: 'black', customdiv: true },
    { key: 'AccountNo', header: 'Account No', backgroundColor: 'lightblue', textColor: 'black', customdiv: true },
    { key: 'AccountName', header: 'Account Name', backgroundColor: 'lightblue', textColor: 'black', customdiv: true },
    { key: 'Status', header: 'Status' , backgroundColor: 'brown', textColor: 'black', customdiv: false },
    { key: 'CreatedAt', header: 'Created At' , backgroundColor: 'peach', textColor: 'black', customdiv: false },
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
}
