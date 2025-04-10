using Skutech.DataAccessLayer.Abstract;
using Skutech.DataAccessLayer.Concrete;
using Skutech.DataAccessLayer.Repositories;
using Skutech.EntityLayer;

namespace Skutech.DataAccessLayer.EntityFramework;

public class EfVehicleDal:GenericRepository<Vehicle>,IVehicle
{
    public EfVehicleDal(Context context) : base(context)
    {
    }
}