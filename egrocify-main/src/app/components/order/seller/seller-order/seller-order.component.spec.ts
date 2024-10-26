import { ComponentFixture, TestBed } from '@angular/core/testing';

import { SellerOrderComponent } from './seller-order.component';

describe('SellerOrderComponent', () => {
  let component: SellerOrderComponent;
  let fixture: ComponentFixture<SellerOrderComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      imports: [SellerOrderComponent]
    })
    .compileComponents();
    
    fixture = TestBed.createComponent(SellerOrderComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
