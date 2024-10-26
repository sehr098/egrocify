import { Component } from '@angular/core';
import {MatTableDataSource, MatTableModule} from '@angular/material/table';
import {MatIconModule} from '@angular/material/icon';
import { ColumnDefinition, SharedTableComponent, TableAction, TableData } from '../../../shared/shared-table/shared-table.component';


@Component({
  selector: 'app-company-list',
  standalone: true,
  imports: [MatTableModule,MatIconModule,SharedTableComponent],
  templateUrl: './company-list.component.html',
  styleUrl: './company-list.component.scss'
})
export class CompanyListComponent {
  companyData: TableData[] = [
    { id: 1, company: 'Test Company', slug: 'test-company', totalBrands: 1, totalProducts: 1 },
    { id: 2, company: 'Another Company', slug: 'another-company', totalBrands: 2, totalProducts: 5 },
    { id: 3, company: 'Test Company', slug: 'test-company', totalBrands: 1, totalProducts: 1 },
    { id: 4, company: 'Another Company', slug: 'another-company', totalBrands: 2, totalProducts: 5 },
    { id: 5, company: 'Test Company', slug: 'test-company', totalBrands: 1, totalProducts: 1 },
    { id: 6, company: 'Another Company', slug: 'another-company', totalBrands: 2, totalProducts: 5 },
    { id: 7, company: 'Test Company', slug: 'test-company', totalBrands: 1, totalProducts: 1 },
    { id: 8, company: 'Another Company', slug: 'another-company', totalBrands: 2, totalProducts: 5 },
    { id: 9, company: 'Test Company', slug: 'test-company', totalBrands: 1, totalProducts: 1 },
    { id: 10, company: 'Another Company', slug: 'another-company', totalBrands: 2, totalProducts: 5 },
    { id: 11, company: 'Another Company', slug: 'another-company', totalBrands: 2, totalProducts: 5 },
    // Add more data as needed
  ];

  columns: ColumnDefinition[] = [
    { key: 'id', header: 'ID' },
    { key: 'company', header: 'Company', backgroundColor: 'orange', textColor: 'black', customdiv: true },
    { key: 'slug', header: 'Slug', backgroundColor: 'purple', textColor: 'black', customdiv: true },
    { key: 'totalBrands', header: 'Total Brands', backgroundColor: 'lightblue', textColor: 'black', customdiv: true },
    { key: 'totalProducts', header: 'Total Products' , backgroundColor: 'lightgreen', textColor: 'black', customdiv: false },
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
