using Skutech.BusinessLayer.Abstract;
using Skutech.BusinessLayer.Concrete;
using Skutech.DataAccessLayer.Abstract;
using Skutech.DataAccessLayer.Concrete;
using Skutech.DataAccessLayer.EntityFramework;

var builder = WebApplication.CreateBuilder(args);

// Add services to the container.
// Learn more about configuring Swagger/OpenAPI at https://aka.ms/aspnetcore/swashbuckle
builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen();
builder.Services.AddControllers();
builder.Services.AddDbContext<Context>();

//Entities

builder.Services.AddScoped<IBrand, EfBrandDal>();
builder.Services.AddScoped<IBrandService, BrandManager>();

builder.Services.AddScoped<IColor, EfColorDal>();
builder.Services.AddScoped<IColorService, ColorManager>();

builder.Services.AddScoped<IPrice, EfPriceDal>();
builder.Services.AddScoped<IPriceService, PriceManager>();

builder.Services.AddScoped<IVehicle, EfVehicleDal>();
builder.Services.AddScoped<IVehicleService, VehicleManager>();

builder.Services.AddScoped<IVehicleModel, EfVehicleModelDal>();
builder.Services.AddScoped<IVehicleModelService, VehicleModelManager>();

builder.Services.AddCors(opt =>
{
    opt.AddPolicy("SkutechApiCors",
        corsPolicyBuilder => { corsPolicyBuilder.AllowAnyOrigin().AllowAnyMethod().AllowAnyHeader(); });
});
// End Entities

var app = builder.Build();

// Configure the HTTP request pipeline.
if (app.Environment.IsDevelopment())
{
    app.UseSwagger();
    app.UseSwaggerUI();
}

app.UseHttpsRedirection();
app.MapControllers();
app.UseCors("SkutechApiCors");
app.UseRouting();

app.Run();