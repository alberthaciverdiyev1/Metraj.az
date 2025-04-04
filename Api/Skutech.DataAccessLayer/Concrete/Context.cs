using System.Net.Mime;
using Microsoft.EntityFrameworkCore;
using Skutech.EntityLayer;

namespace Skutech.DataAccessLayer.Concrete;

public class Context : DbContext
{
    protected override void OnConfiguring(DbContextOptionsBuilder optionsBuilder)
    {
        optionsBuilder.UseMySql("Server=127.0.0.1;Port=3306;Database=skutech;User=root;Password=",
            new MySqlServerVersion(new Version(8, 0, 35))
        );
    }

    public DbSet<Brand> Brands { get; set; }
    public DbSet<Color> Colors { get; set; }
    public DbSet<Vehicle> Vehicles { get; set; }
    public DbSet<VehicleModel> Models { get; set; }
    public DbSet<Price> Prices { get; set; }
}