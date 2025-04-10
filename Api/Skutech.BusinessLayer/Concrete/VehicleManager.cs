using Skutech.BusinessLayer.Abstract;
using Skutech.DataAccessLayer.Abstract;
using Skutech.EntityLayer;

namespace Skutech.BusinessLayer.Concrete;

public class VehicleManager:IGenericService<Vehicle>
{
    private readonly IVehicle _vehicle;

    public VehicleManager(IVehicle vehicle)
    {
        _vehicle = vehicle;
    }
    public void TInsert(Vehicle entity)
    {
        _vehicle.Insert(entity);
    }

    public void TUpdate(Vehicle entity)
    {
        _vehicle.Update(entity);
    }

    public void TDelete(Vehicle entity)
    {
        _vehicle.Delete(entity);
    }

    public Vehicle? TGetElementById(int id)
    {
        return _vehicle.GetElementById(id);
    }

    public List<Vehicle> TGetList()
    {
        return _vehicle.GetList();
    }
}